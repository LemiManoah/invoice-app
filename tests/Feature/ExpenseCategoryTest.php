<?php

use App\Models\ExpenseCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->user = User::factory()->create();
    // In many setups, roles/permissions are seeded, but let's just create permissions directly for the test if they don't exist
    $permissions = ['expenses.view', 'expenses.create', 'expenses.update'];
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }
    $this->user->givePermissionTo($permissions);
});

it('can view expense categories index', function () {
    $this->actingAs($this->user)
        ->get(route('expense-categories.index'))
        ->assertOk();
});

it('can create an expense category', function () {
    $this->actingAs($this->user)
        ->post(route('expense-categories.store'), [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'is_active' => true,
        ])
        ->assertRedirect(route('expense-categories.index'));

    $this->assertDatabaseHas('expense_categories', [
        'name' => 'Test Category',
    ]);
});

it('can update an expense category', function () {
    $category = ExpenseCategory::create([
        'name' => 'Original',
        'is_active' => true,
    ]);

    $this->actingAs($this->user)
        ->put(route('expense-categories.update', $category), [
            'name' => 'Updated Name',
            'description' => 'Updated Desc',
            'is_active' => false,
        ])
        ->assertRedirect(route('expense-categories.index'));

    $this->assertDatabaseHas('expense_categories', [
        'id' => $category->id,
        'name' => 'Updated Name',
        'is_active' => false,
    ]);
});

it('can delete an expense category', function () {
    $category = ExpenseCategory::create([
        'name' => 'To Be Deleted',
        'is_active' => true,
    ]);

    $this->actingAs($this->user)
        ->delete(route('expense-categories.destroy', $category))
        ->assertRedirect(route('expense-categories.index'));

    $this->assertDatabaseMissing('expense_categories', [
        'id' => $category->id,
    ]);
});
