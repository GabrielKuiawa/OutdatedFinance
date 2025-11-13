<?php

$resourcesToJson = [];
foreach ($resources as $resource) {
    $resourcesToJson[] = [
        'id' => $resource->id,
        'file_path' => $_ENV['API_HOST'] . $resource->file_path,
    ];
}
$json['results'] = $resourcesToJson;
