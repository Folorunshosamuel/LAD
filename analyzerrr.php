<?php
// Database connection (REPLACE WITH YOUR CREDENTIALS)
$db = new PDO('mysql:host=localhost;dbname=lad', 'root', 'root');

// Get bill ID from the request
$billId = isset($_GET['bill_id']) ? $_GET['bill_id'] : null;

if ($billId === null) {
    die("Bill ID not provided.");
}

// Fetch bill text from the database
$stmt = $db->prepare("SELECT billAnalysis FROM bills WHERE id = :id");
$stmt->bindParam(':id', $billId, PDO::PARAM_INT);
$stmt->execute();
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bill) {
    die("Bill not found.");
}

$billText = $bill['billAnalysis'];

// Preprocess the text (optional -  remove extra whitespace)
$cleanedBillText = preg_replace('/\s+/', ' ', $billText);

// Gemini API credentials (REPLACE WITH YOUR ACTUAL API KEY -  Use environment variables in production!)
$apiKey = getenv('GEMINI_API_KEY');
if (!$apiKey) {
    die("GEMINI_API_KEY environment variable not set.");
}
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

// Construct a comprehensive prompt for Gemini
$prompt = "Analyze the following legislative bill:\n\n" . $cleanedBillText . "\n\nProvide a concise summary of the key provisions.  Describe the potential operational and fiscal impacts.  Discuss any significant legal implications.  Finally, offer recommendations based on your analysis.";


// Gemini API request using cURL
$data = [
    'model' => 'gemini-1.5-flash',
    'prompt' => $prompt,
    'temperature' => 0.7, // Adjust as needed
    'maxOutputTokens' => 1024 // Adjust as needed
];

$headers = [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode != 200) {
    die("Gemini API request failed: HTTP Status Code: $httpCode. Curl Error: $error");
}

$decodedResponse = json_decode($response, true);

if (isset($decodedResponse['candidates'][0]['content'])) {
    $analysisResult = $decodedResponse['candidates'][0]['content'];
    echo "<h2>Analysis Result:</h2><pre>" . htmlspecialchars($analysisResult) . "</pre>";
} else {
    die("Unexpected response from Gemini API.");
}

?>