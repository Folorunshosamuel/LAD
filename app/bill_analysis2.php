<?php
include 'header.php'; // Include your global header

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $apiKey = 'sk-CF6Ei4lR1obhzDVoJypGte6IR1puM0Y_WTRcpe0t8LT3BlbkFJt3Q14nqtB9ibHj1aYX_eJMpEF4gYHRjKir25jBEwAA';
    $billContent = $_POST['billContent'] ?? '';
    $selectedPrompt = $_POST['prompt'] ?? 'Summarize the key provisions of the bill.';

    $prompt = "$selectedPrompt\n\nBill Content:\n$billContent";

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

    $responseData = json_decode($response, true);
    $analysis = $responseData['choices'][0]['message']['content'] ?? 'No analysis could be generated.';
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
    <style>
        #loader {
            display: none;
            text-align: center;
        }
        #loader .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Bill Analysis Tool</h1>
    <form id="billForm" class="p-4 bg-white shadow rounded">
        <div class="mb-3">
            <label for="billContent" class="form-label">Paste the Bill Content</label>
            <textarea class="form-control" id="billContent" name="billContent" rows="6" placeholder="Enter the bill content here..."></textarea>
        </div>
        <div class="mb-3">
            <label for="prompt" class="form-label">Select Analysis Type</label>
            <select id="prompt" name="prompt" class="form-select">
                <option value="Summarize the key provisions of the bill.">Summarize Key Provisions</option>
                <option value="Describe the operational and fiscal impact of this bill.">Operational and Fiscal Impact</option>
                <option value="Discuss the legal implications of this bill.">Legal Implications</option>
                <option value="Provide recommendations for the bill.">Recommendations</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary w-100" id="analyzeBtn">Generate Analysis</button>
    </form>
    <div id="loader" class="mt-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Analyzing the bill, please wait...</p>
    </div>
    <div class="mt-5" id="analysisResult" style="display: none;">
        <h3 class="text-center">Analysis Result</h3>
        <div id="analysisContent" class="p-4 bg-white shadow rounded"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('analyzeBtn').addEventListener('click', async () => {
        const billContent = document.getElementById('billContent').value;
        const prompt = document.getElementById('prompt').value;

        if (!billContent) {
            alert('Please paste or type the content of the bill.');
            return;
        }

        document.getElementById('loader').style.display = 'block';
        document.getElementById('analysisResult').style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('billContent', billContent);
            formData.append('prompt', prompt);

            const response = await axios.post('', formData);
            document.getElementById('loader').style.display = 'none';
            document.getElementById('analysisResult').style.display = 'block';
            document.getElementById('analysisContent').innerText = response.data.analysis;
        } catch (error) {
            alert('An error occurred while generating the analysis. Please try again.');
            document.getElementById('loader').style.display = 'none';
            console.error(error);
        }
    });
</script>
</body>
</html>
