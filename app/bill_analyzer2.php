<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Load your OpenAI API key
    $apiKey = 'sk-CF6Ei4lR1obhzDVoJypGte6IR1puM0Y_WTRcpe0t8LT3BlbkFJt3Q14nqtB9ibHj1aYX_eJMpEF4gYHRjKir25jBEwAA';

    $billContent = $_POST['billContent'] ?? '';

    // Define the prompt
    $prompt = "
Summarize the key provisions of the bill in this section.
Describe the operational and fiscal impact this bill.
Describe any legal issues that this bill raises.
Provide recommendations.

Bill Content:
$billContent
";

    // Make the GPT API request
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'system', 'content' => 'You are an assistant helping to analyze legal bills.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'max_tokens' => 1500,
        'temperature' => 0.7,
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Parse the response
    $responseData = json_decode($response, true);
    $analysis = $responseData['choices'][0]['message']['content'] ?? 'No analysis could be generated.';

    // Return the response
    echo json_encode(['analysis' => $analysis]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Analysis Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Bill Analysis Tool</h1>
    <form id="billForm">
        <div class="mb-3">
            <label for="billContent" class="form-label">Paste the Bill Content</label>
            <textarea class="form-control" id="billContent" name="billContent" rows="10" placeholder="Enter the bill content here..."></textarea>
        </div>
        <button type="button" class="btn btn-primary" id="analyzeBtn">Generate Analysis</button>
    </form>
    <div class="mt-5" id="analysisResult" style="display: none;">
        <h3>Analysis</h3>
        <div id="analysisContent"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('analyzeBtn').addEventListener('click', async () => {
        const billContent = document.getElementById('billContent').value;
        if (!billContent) {
            alert('Please paste or type the content of the bill.');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('billContent', billContent);

            const response = await axios.post('', formData);
            document.getElementById('analysisResult').style.display = 'block';
            document.getElementById('analysisContent').innerText = response.data.analysis;
        } catch (error) {
            alert('An error occurred while generating the analysis. Please try again.');
            console.error(error);
        }
    });
</script>
</body>
</html>