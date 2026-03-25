<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Appearance\UpdateAppearanceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppearanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    public function edit(): View
    {
        return view('settings.appearance');
    }

    public function update(UpdateAppearanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        (new UpdateAppearanceAction)($request->user(), $data);

        return back();
    }
}
