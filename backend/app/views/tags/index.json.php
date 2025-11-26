<?php

$tagsToJson = [];
foreach ($tags as $tag) {
    $tagsToJson[] = [
        'id' => $tag->id,
        'name' => $tag->name,
        'user_id' => $tag->user_id 
    ];
}

$json['tags'] = $tagsToJson;
