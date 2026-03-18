<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit(1);
}

// ===================================================
//  PASTE YOUR GROQ API KEY HERE
//  Get one free at: https://console.groq.com/keys
// ===================================================
$GROQ_API_KEY = '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';
// ===================================================

$body = file_get_contents('php://input');

if (empty($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty request body']);
    exit(1);
}

$input = json_decode($body, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit(1);
}

// Build Groq-compatible request payload
// Groq uses the OpenAI chat completions format
$groqPayload = [
    'model'       => 'llama-3.3-70b-versatile',  // Current recommended Groq model
    'max_tokens'  => 1000,
    'temperature' => 0.7,
    'messages'    => []
];

// Inject system prompt as the first message if provided
if (!empty($input['system'])) {
    $groqPayload['messages'][] = [
        'role'    => 'system',
        'content' => $input['system']
    ];
}

// Append conversation history
if (!empty($input['messages']) && is_array($input['messages'])) {
    foreach ($input['messages'] as $msg) {
        $role    = $msg['role'] ?? '';
        $content = $msg['content'] ?? '';

        // Groq uses 'assistant' not 'model', remap if needed
        if ($role === 'model') {
            $role = 'assistant';
        }

        if (in_array($role, ['user', 'assistant', 'system']) && !empty($content)) {
            $groqPayload['messages'][] = [
                'role'    => $role,
                'content' => $content
            ];
        }
    }
}

if (empty($groqPayload['messages'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No messages provided']);
    exit(1);
}

// Call Groq API (OpenAI-compatible endpoint)
$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($groqPayload),
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $GROQ_API_KEY,
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to reach Groq API: ' . $curlError]);
    exit(1);
}

// Groq returns OpenAI format: { choices: [ { message: { content: "..." } } ] }
// We convert it to Anthropic-style format so chatbot.js needs minimal changes
$groqData = json_decode($response, true);

if (isset($groqData['choices'][0]['message']['content'])) {
    $replyText = $groqData['choices'][0]['message']['content'];

    // Return in Anthropic-compatible format so chatbot.js works unchanged
    http_response_code(200);
    echo json_encode([
        'content' => [
            ['type' => 'text', 'text' => $replyText]
        ]
    ]);
} else {
    // Pass through the Groq error response as-is
    http_response_code($httpCode);
    echo $response;
}