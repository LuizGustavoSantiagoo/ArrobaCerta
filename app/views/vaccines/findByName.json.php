<?php

$vaccinesToJson = [];

foreach ($vaccine as $item) {
    $vaccinesToJson[] = [
        'id' => $item->id,
        'name' => $item->name,
        'description' => $item->description,
    ];
}

$json['vaccines'] = $vaccinesToJson;
