<?php

test('profile page is displayed', function () {
    $this->actingAs(createUserWithPermissions(['settings.profile.update']));

    $this->get('/settings/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = createUserWithPermissions(['settings.profile.update']);

    $response = $this
        ->actingAs($user)
        ->put('/settings/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = createUserWithPermissions(['settings.profile.update']);

    $response = $this
        ->actingAs($user)
        ->put('/settings/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = createUserWithPermissions();

    $response = $this
        ->actingAs($user)
        ->delete('/settings/profile');

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});
