<?php

function analyzeBillText($billText) {
    $url = "http://localhost:5000/analyze";  // Adjust URL if using a different host/port
    $data = json_encode(["text" => $billText]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>