<?php

$groupsToJson = [];
foreach ($groups as $group) {
    $groupsToJson[] = [
        'id' => $group->id,
        'name' =>  $group->name,
        'description' => $group->description,
        'owner_user_id' => $group->owner_user_id,
        'created_at' => $group->created_at,
    ];
}
$json['groups'] = $groupsToJson;
