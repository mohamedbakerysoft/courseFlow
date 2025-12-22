<?php

use App\Actions\Dashboard\Users\UpdateUserStatusAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates user disabled status via action', function () {
    $user = User::factory()->create(['is_disabled' => false]);
    $action = new UpdateUserStatusAction;
    $action->execute($user, true);
    expect($user->fresh()->is_disabled)->toBeTrue();
});
