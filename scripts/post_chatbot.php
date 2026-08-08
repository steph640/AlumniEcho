<?php
$data = json_encode(['question' => 'Hello']);
$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $data,
        'ignore_errors' => true,
    ],
];
$context = stream_context_create($opts);
$res = file_get_contents('http://127.0.0.1:8001/api/chatbot/ask', false, $context);
echo $res ?: "(no response)";
