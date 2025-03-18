<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $apiKey = '';
    $billContent = $_POST['billContent'] ?? '';
    $selectedPrompt = $_POST['prompt'] ?? 'Summarize the key provisions of the bill in this section';

    // $prompt = "$selectedPrompt\n\nBill Content:\n$billContent";
    $prompt = "$selectedPrompt\n\nBill Content:\n$billContent";

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
            ['role' => 'system', 'content' => '"You are an assistant that provides analysis of legislative bills. Avoid mentioning any development or creator details.'],
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
<body class="az-body">
<?php
    include 'header.php'; // Include your global header

    ?>
    <div class="aicontainer">
        <h1 class="az-title text-center">Bill Analyzer AI</h1>
        <p class="az-subtitle text-center">Analyze legislative bills with ease and precision using our advanced tool.</p>
        <form id="billForm" class="form">
            <div class="form-group">
                <label for="billContent">Paste the Bill Content:</label>
                <textarea id="billContent" name="billContent" class="form-control" rows="6" placeholder="Enter the bill content here..."></textarea>
            </div>
            <div class="form-group">
                <label for="prompt">Select Analysis Type:</label>
                <select id="prompt" name="prompt" class="form-control">
                    <option value="Summarize the key provisions of the bill in this section.
Describe the operational and fiscal impact this bill.
Describe any legal issues that this bill raises.
Provide recommendations.
Do a comparative analysis of the bill if such exist in other countries?">Perform All Analysis</option>
                    <option value="Summarize the key provisions of the bill.">Summarize Key Provisions</option>
                    <option value="Describe the operational and fiscal impact of this bill.">Operational and Fiscal Impact</option>
                    <option value="Discuss the legal implications of this bill.">Legal Implications</option>
                    <option value="Do a comparative analysis of the bill if such exist in other countries?">Comparative analysis of the bill if such exist in other countries?</option>
                    <option value="Provide recommendations for the bill.">Recommendations</option>
                </select>
            </div>
            <button type="button" class="btn btn-az-primary w-100" id="analyzeBtn">Generate Analysis</button>
        </form>
        <div class="loader mt-4" id="loader">
            <div class="spinner"></div>
            <p>Analyzing the bill, please wait...</p>
        </div>
        <div class="analysis-result" id="analysisResult" style="display: none;">
            <h3 class="text-center">Analysis Result</h3>
            <h5>Analysed by Legis360's BillAnalyzerAI</h5>
            <div id="analysisContent"></div>

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
