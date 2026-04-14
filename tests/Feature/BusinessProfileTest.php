<?php

use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::factory()->create();

    $permissions = [
        'business-profile.view',
        'business-profile.create',
        'business-profile.update',
        'business-profile.delete',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]);
    }

    $this->user->givePermissionTo($permissions);
});

it('can view the business profile page', function () {
    BusinessProfile::query()->create([
        'name' => 'Acme Tailors',
    ]);

    $this->actingAs($this->user)
        ->get(route('business-profile.show'))
        ->assertOk()
        ->assertSee('Business Information')
        ->assertSee('Acme Tailors');
});

it('redirects to the edit screen when no business profile exists yet', function () {
    $this->actingAs($this->user)
        ->get(route('business-profile.show'))
        ->assertRedirect(route('business-profile.edit'));
});

it('can create a business profile with a logo and drawn signature', function () {
    $signatureData = 'data:image/png;base64,'.base64_encode(
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9WnV6u8AAAAASUVORK5CYII=', true)
    );

    $response = $this->actingAs($this->user)->post(route('business-profile.store'), [
        'name' => 'Acme Tailors',
        'location' => 'Kampala',
        'email' => 'hello@acme.test',
        'contacts' => '+256700000000',
        'address' => 'Plot 1, Main Street',
        'po_box' => 'P.O. Box 123',
        'logo' => UploadedFile::fake()->image('logo.png'),
        'signature_data' => $signatureData,
    ]);

    $response->assertRedirect(route('business-profile.show'));

    $profile = BusinessProfile::query()->first();

    expect($profile)->not->toBeNull();

    $this->assertDatabaseHas('business_profiles', [
        'name' => 'Acme Tailors',
        'email' => 'hello@acme.test',
    ]);

    Storage::disk('public')->assertExists($profile->logo_path);
    Storage::disk('public')->assertExists($profile->signature_path);
});

it('can update a business profile and replace stored files', function () {
    Storage::disk('public')->put('business-profile/logos/old-logo.png', 'old-logo');
    Storage::disk('public')->put('business-profile/signatures/old-signature.png', 'old-signature');

    $profile = BusinessProfile::query()->create([
        'name' => 'Original Name',
        'location' => 'Original Location',
        'email' => 'old@acme.test',
        'contacts' => '12345',
        'address' => 'Original Address',
        'po_box' => 'Box 10',
        'logo_path' => 'business-profile/logos/old-logo.png',
        'signature_path' => 'business-profile/signatures/old-signature.png',
    ]);

    $response = $this->actingAs($this->user)->put(route('business-profile.update'), [
        'name' => 'Updated Name',
        'location' => 'Updated Location',
        'email' => 'new@acme.test',
        'contacts' => '67890',
        'address' => 'Updated Address',
        'po_box' => 'Box 99',
        'logo' => UploadedFile::fake()->image('new-logo.jpg'),
        'signature_upload' => UploadedFile::fake()->image('new-signature.png'),
    ]);

    $response->assertRedirect(route('business-profile.show'));

    $profile->refresh();

    expect($profile->name)->toBe('Updated Name');
    expect($profile->email)->toBe('new@acme.test');

    Storage::disk('public')->assertMissing('business-profile/logos/old-logo.png');
    Storage::disk('public')->assertMissing('business-profile/signatures/old-signature.png');
    Storage::disk('public')->assertExists($profile->logo_path);
    Storage::disk('public')->assertExists($profile->signature_path);
});
