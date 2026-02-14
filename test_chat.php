<?php

// Simple test script to verify the chat endpoint
$url = 'http://127.0.0.1:8000/api/chat';
$data = [
    'message' => 'Bonjour',
    'history' => []
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'timeout' => 30
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "ERROR: Failed to connect to the endpoint\n";
    echo "HTTP Response Headers:\n";
    print_r($http_response_header ?? 'No headers available');
} else {
    echo "SUCCESS: Got response from endpoint\n";
    echo "Response:\n";
    echo $result . "\n";
    
    $json = json_decode($result, true);
    if (isset($json['reply'])) {
        echo "\n=== AI Reply ===\n";
        echo $json['reply'] . "\n";
        
        // Check if greeting is present
        if (strpos($json['reply'], 'Bonjour') !== false && strpos($json['reply'], 'portfolio') !== false) {
            echo "\n✓ GREETING VERIFIED: Response contains required greeting!\n";
        } else {
            echo "\n✗ WARNING: Response may not contain the mandatory greeting\n";
        }
    }
}
