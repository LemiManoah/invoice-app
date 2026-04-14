<?php

use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = createUserWithPermissions(['settings.password.update']);

    $response = $this
        ->actingAs($user)
        ->from('/settings/profile')
        ->put('/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = createUserWithPermissions(['settings.password.update']);

    $response = $this
        ->actingAs($user)
        ->from('/settings/profile')
        ->put('/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect('/settings/profile');
});
