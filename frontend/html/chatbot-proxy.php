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
//  GROQ API KEY - store in environment variable
//  Set via: putenv("GROQ_API_KEY=your_key_here")
//  or in your server's environment config
// ===================================================
$API_KEY = getenv('GROQ_API_KEY');

if (empty($API_KEY)) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured']);
    exit(1);
}
// ===================================================

// Include database connection
require_once('../../database/db_connect.php');

// Get request body
$body = file_get_contents('php://input');
$requestData = json_decode($body, true);

// Basic validation
if (empty($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty request body']);
    exit(1);
}

// Extract the user message
$userMessage = '';
if (!empty($requestData['messages'])) {
    // Get the last user message
    $messages = $requestData['messages'];
    foreach (array_reverse($messages) as $msg) {
        if ($msg['role'] === 'user') {
            // ✅ FIX 1 & 2: Use assignment (=) not comparison (===)
            $content = $msg['content'];
            if (is_array($content)) {
                $userMessage = $content[0]['text'];
            } else {
                $userMessage = $content;
            }
            break;
        }
    }
}

// Fetch database context (wine inventory)
$wineContext = getWineContext($conn);

// Build the system prompt with database context
$systemPrompt = "You are \"Edward\", a friendly and knowledgeable AI chatbot for the Wine Exchange website. You help users with:

- Wine recommendations (red, white, rosé, sparkling, by budget, occasion, or taste)
- Food and wine pairings
- How the wine exchange works (users can buy and sell wines, list bottles, browse listings)
- Returns and refunds policy: customers can return unopened bottles within 14 days of delivery for a full refund. Damaged or faulty wine is covered and can be returned at any time with evidence. Returns are not accepted for opened bottles unless the wine is corked/faulty.
- Selling wine: users can list their bottles on the platform, set a price, and the exchange takes a small commission (typically 5-10%). Wines must be properly stored and described accurately.
- Shipping: wines are shipped in protective packaging. Standard delivery is 3-5 working days. Express next-day delivery is available. Temperature-sensitive wines may be held during extreme weather.
- Wine storage: wines should be stored on their side at 12-14°C, away from light. The exchange can also offer bonded storage.
- General wine education: regions, grapes, tasting notes, vintages, serving temperatures

Keep responses warm, knowledgeable, and concise (2-4 sentences max unless a list is helpful). Use light wine-related language naturally. Never make up specific prices or listings.

CURRENT WINE INVENTORY:
" . $wineContext . "

When users ask about specific wines or availability, reference this inventory data.";

// Build Groq API request (OpenAI format)
$groqRequest = [
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        [
            'role' => 'system',
            'content' => $systemPrompt
        ],
        [
            'role' => 'user',
            'content' => $userMessage
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 512
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($groqRequest),
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $API_KEY,
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(502);
    error_log("CURL Error: " . $curlError);
    echo json_encode(['error' => 'Failed to reach Groq API: ' . $curlError]);
    exit(1);
}

// Parse Groq response (OpenAI format)
$groqResponse = json_decode($response, true);

error_log("DEBUG: Groq HTTP Code = " . $httpCode);
error_log("DEBUG: Groq Response = " . $response);

if ($httpCode === 200 && !empty($groqResponse['choices'])) {
    // ✅ FIX 3: Use assignment (=) not comparison (===)
    $groqText = $groqResponse['choices'][0]['message']['content'];

    // Send back in Anthropic format for frontend compatibility
    $formattedResponse = [
        'content' => [
            ['type' => 'text', 'text' => $groqText]
        ]
    ];

    http_response_code(200);
    echo json_encode($formattedResponse);
} else {
    http_response_code($httpCode === 200 ? 400 : $httpCode);
    error_log("ERROR: Failed to get response from Groq API. HTTP Code: " . $httpCode . ", Response: " . $response);
    echo json_encode(['error' => 'Failed to get response from Groq API', 'debug' => $response]);
}

// ===================================================
// Helper function to fetch wine context from database
// ===================================================
function getWineContext($conn) {
    try {
        $query = "SELECT wineName, wineRegion, category, price, stock FROM wines WHERE active = TRUE ORDER BY category, wineName LIMIT 20";
        $result = $conn->query($query);

        if (!$result) {
            return "Unable to fetch wine data.";
        }

        $context = "";
        while ($row = $result->fetch_assoc()) {
            $stock = ($row['stock'] === null) ? "Available" : ($row['stock'] > 0 ? $row['stock'] . " bottles" : "Out of stock");
            $context .= "- {$row['wineName']} ({$row['wineRegion']}) - {$row['category']} - £{$row['price']} - {$stock}\n";
        }

        return !empty($context) ? $context : "No wines currently available.";
    } catch (Exception $e) {
        return "Wine inventory unavailable.";
    }
}
?>