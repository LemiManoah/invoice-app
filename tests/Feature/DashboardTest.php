<?php

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = createUserWithPermissions(['dashboard.view']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk();
});
