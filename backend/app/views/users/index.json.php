<?php

$usersToJson = [];
foreach ($users as $user) {
    $usersToJson[] = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'password' => $user->encrypted_password,
        'avatar' => $user->avatar,
        'created_at' => $user->created_at,
        'deleted_at' => $user->deleted_at,
    ];
}
$json['users'] = $usersToJson;
