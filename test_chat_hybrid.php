<?php

// Function to test chat endpoint
function testChat($message) {
    $url = 'http://127.0.0.1:8000/api/chat';
    $data = ['message' => $message];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 5
        ]
    ];

    echo "\nTesting message: '{$message}'\n";
    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "ERROR: Failed to connect\n";
    } else {
        $json = json_decode($result, true);
        echo "Source: " . ($json['source'] ?? 'unknown') . "\n";
        echo "Reply: " . substr($json['reply'], 0, 100) . "...\n";
    }
}

// Test 1: Known topic
testChat("Quelles sont vos compétences ?");

// Test 2: Unknown topic
testChat("Quelle est la capitale de Mars ?");
