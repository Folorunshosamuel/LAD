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
Do a comparative analysis of the bill if such exist in other countries?

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
    <link rel="stylesheet" href="lad.css"> <!-- Ensure this path matches your project's stylesheet -->
    <style>
        .aicontainer {
            margin: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .loader {
            display: none;
            text-align: center;
        }
        .loader .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .analysis-result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include your global header

    ?>
<div class="aicontainer mt-5">
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
        <div>
            <h3> Analysed by Yiaga Africa BillAnalyzerAi</h3>
        </div>
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
