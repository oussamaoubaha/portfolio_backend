<?php

// Direct API call to list models
$apiKey = 'AIzaSyCF_aCl14ACtRKtIY_ijcJASAzu0P-7rYc';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";

$options = [
    'http' => [
        'method'  => 'GET',
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$json = json_decode($result, true);

if (isset($json['models'])) {
    foreach ($json['models'] as $model) {
        // Output clean name: models/gemini-pro -> gemini-pro
        $name = str_replace('models/', '', $model['name']);
        echo $name . "\n";
    }
} else {
    echo "ERROR: " . ($json['error']['message'] ?? 'Unknown error') . "\n";
}
