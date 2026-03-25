<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Password\UpdatePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.password', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        (new UpdatePasswordAction)($request->user(), $data['password']);

        return back()->with('status', 'password-updated');
    }
}
