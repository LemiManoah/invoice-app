<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

final readonly class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.view', only: ['index']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.update', only: ['edit', 'update']),
        ];
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = trim((string) $request->query('search', ''));
        $role = trim((string) $request->query('role', ''));
        $status = trim((string) $request->query('status', ''));

        $users = User::query()
            ->with('roles')
            ->when(
                $search !== '',
                static function (Builder $query) use ($search): void {
                    $query->where(function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', sprintf('%%%s%%', $search))
                            ->orWhere('email', 'like', sprintf('%%%s%%', $search))
                            ->orWhere('phone', 'like', sprintf('%%%s%%', $search));
                    });
                },
            )
            ->when($role !== '', static fn (Builder $query) => $query->role($role))
            ->when($status !== '', static fn (Builder $query) => $query->where('is_active', $status === 'active'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('users.index', compact('users', 'roles', 'search', 'role', 'status'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): RedirectResponse
    {
        $this->authorize('create', User::class);

        $user = $action->handle($request->validated());

        return to_route('users.edit', $user)->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $roles = Role::query()->orderBy('name')->pluck('name');
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->all();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        $this->authorize('update', $user);

        $action->handle($user, $request->validated());

        return to_route('users.edit', $user)->with('status', 'User updated successfully.');
    }
}
