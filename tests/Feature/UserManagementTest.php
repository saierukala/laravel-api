<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a user password can be changed from the edit page update', function () {
    $user = User::factory()->create([
        'password' => 'old-password',
    ]);

    $response = $this->put(route('users.update', $user), [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('users.index'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('a blank edit password keeps the existing password', function () {
    $user = User::factory()->create([
        'password' => 'old-password',
    ]);

    $originalPassword = $user->password;

    $response = $this->put(route('users.update', $user), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertRedirect(route('users.index'));

    $user->refresh();

    expect($user->name)->toBe('Updated Name')
        ->and($user->email)->toBe('updated@example.com')
        ->and($user->password)->toBe($originalPassword)
        ->and(Hash::check('old-password', $user->password))->toBeTrue();
});
