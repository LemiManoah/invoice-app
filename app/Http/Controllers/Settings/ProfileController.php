<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Profile\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final readonly class ProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings.profile.update', only: ['edit', 'update']),
        ];
    }

    public function edit(Request $request): View
    {
        $this->authorize('updateProfile', $request->user());

        return view('settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('updateProfile', $user);

        $action->handle($user, $request->validated());

        return to_route('settings.profile.edit')->with('status', __('Profile updated successfully'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('delete', $user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
