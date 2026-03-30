<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessProfileRequest;
use App\Http\Requests\UpdateBusinessProfileRequest;
use App\Models\BusinessProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final readonly class BusinessProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:business-profile.view', only: ['show', 'edit']),
            new Middleware('permission:business-profile.create', only: ['store']),
            new Middleware('permission:business-profile.update', only: ['update']),
            new Middleware('permission:business-profile.delete', only: ['destroy']),
        ];
    }

    public function show(): View|RedirectResponse
    {
        $this->authorize('viewAny', BusinessProfile::class);

        $businessProfile = BusinessProfile::query()->first();

        if ($businessProfile === null) {
            return to_route('business-profile.edit')
                ->with('error', 'Add your business profile details to get started.');
        }

        return view('business-profile.show', compact('businessProfile'));
    }

    public function edit(): View
    {
        $this->authorize('viewAny', BusinessProfile::class);

        $businessProfile = BusinessProfile::query()->first();

        return view('business-profile.edit', compact('businessProfile'));
    }

    public function store(StoreBusinessProfileRequest $request): RedirectResponse
    {
        if (BusinessProfile::query()->exists()) {
            return to_route('business-profile.edit')
                ->with('error', 'A business profile already exists. Update the current profile instead.');
        }

        $profile = BusinessProfile::query()->create(
            $this->buildPayload($request)
        );

        return to_route('business-profile.show')
            ->with('success', sprintf('%s profile created successfully.', $profile->name));
    }

    public function update(UpdateBusinessProfileRequest $request): RedirectResponse
    {
        $profile = BusinessProfile::query()->firstOrFail();
        $this->authorize('update', $profile);

        $profile->update($this->buildPayload($request, $profile));

        return to_route('business-profile.show')->with('success', 'Business profile updated successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $profile = BusinessProfile::query()->firstOrFail();
        $this->authorize('delete', $profile);

        $this->deleteFile($profile->logo_path);
        $this->deleteFile($profile->signature_path);

        $profile->delete();

        return to_route('business-profile.edit')->with('success', 'Business profile deleted successfully.');
    }

    private function buildPayload(Request $request, ?BusinessProfile $profile = null): array
    {
        $data = $request->safe()->except([
            'logo',
            'signature_upload',
            'signature_data',
            'remove_logo',
            'remove_signature',
        ]);

        if ($request->boolean('remove_logo') && $profile?->logo_path !== null) {
            $this->deleteFile($profile->logo_path);
            $data['logo_path'] = null;
        } else {
            $data['logo_path'] = $profile?->logo_path;
        }

        if ($request->hasFile('logo')) {
            $this->deleteFile($profile?->logo_path);
            $data['logo_path'] = $request->file('logo')->store('business-profile/logos', 'public');
        }

        if ($request->boolean('remove_signature') && $profile?->signature_path !== null) {
            $this->deleteFile($profile->signature_path);
            $data['signature_path'] = null;
        } else {
            $data['signature_path'] = $profile?->signature_path;
        }

        $signatureData = trim((string) $request->input('signature_data', ''));

        if ($signatureData !== '') {
            $this->deleteFile($profile?->signature_path);
            $data['signature_path'] = $this->storeDrawnSignature($signatureData);
        } elseif ($request->hasFile('signature_upload')) {
            $this->deleteFile($profile?->signature_path);
            $data['signature_path'] = $request->file('signature_upload')->store('business-profile/signatures', 'public');
        }

        return $data;
    }

    private function storeDrawnSignature(string $signatureData): string
    {
        if (! str_starts_with($signatureData, 'data:image/png;base64,')) {
            throw ValidationException::withMessages([
                'signature_data' => 'The drawn signature format is invalid.',
            ]);
        }

        $decoded = base64_decode(Str::after($signatureData, 'data:image/png;base64,'), true);

        if ($decoded === false) {
            throw ValidationException::withMessages([
                'signature_data' => 'The drawn signature could not be processed.',
            ]);
        }

        $path = 'business-profile/signatures/'.Str::uuid().'.png';

        Storage::disk('public')->put($path, $decoded);

        return $path;
    }

    private function deleteFile(?string $path): void
    {
        if ($path !== null && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }
}
