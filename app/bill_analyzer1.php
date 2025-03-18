<?php
// Include database connection
include 'db_connect.php';

// Placeholder for analysis result
$analysisResult = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billId = $_POST['bill_id'];

    // Fetch bill file information
    $stmt = $db->prepare("SELECT billFile, billAnalysis FROM Bills WHERE id = :id"); // Added billContent column
    $stmt->bindParam(':id', $billId, PDO::PARAM_INT);
    $stmt->execute();
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($bill) {
        $billContent = $bill['billContent']; // Get content directly from DB

        if($billContent){
          $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";
          $apiKey = getenv('GEMINI_API_KEY'); // Get API key from environment variable

          if (!$apiKey) {
              $analysisResult = "Error: GEMINI_API_KEY environment variable not set.";
          } else {
              $data = [
                  'model' => 'gemini-1.5-flash',
                  'prompt' => $billContent, // Send content directly
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

              if ($httpCode == 200) {
                  $decodedResponse = json_decode($response, true);
                  if (isset($decodedResponse['candidates'][0]['content'])) {
                      $analysisResult = $decodedResponse['candidates'][0]['content'];
                  } else {
                      $analysisResult = "Error: Unexpected API response format.";
                  }
              } else {
                  $analysisResult = "Error: Gemini API request failed. HTTP Status Code: $httpCode. Curl Error: $error";
              }
          }
        }else{
          $analysisResult = "Error: Bill content not found in the database.";
        }
    } else {
        $analysisResult = "Error: Bill not found in the database.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Analyzer</title>
    <link rel="stylesheet" href="lad.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Bill Analyzer</h1>

        <!-- Form to Select Bill -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="bill_id">Select a Bill to Analyze:</label>
                <select name="bill_id" id="bill_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Bill --</option>
                    <?php foreach ($bills as $bill): ?>
                        <option value="<?= htmlspecialchars($bill['id']); ?>">
                            <?= htmlspecialchars($bill['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Analyze</button>
        </form>

        <!-- Analysis Result -->
        <?php if ($analysisResult): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Analysis Result</h3>
                </div>
                <div class="card-body">
                    <pre><?= htmlspecialchars($analysisResult); ?></pre>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
