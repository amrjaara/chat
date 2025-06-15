<?php
$responseText = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["prompt"])) {
    $prompt = $_POST["prompt"];

    $data = json_encode([
        "model" => "deepseek-r1",
        "prompt" => $prompt,
        "stream" => false
    ]);

    $ch = curl_init("http://172.17.0.1:11434/api/generate");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Content-Length: " . strlen($data)
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        $responseText = "Curl Error: " . curl_error($ch);
    } else {
        $json = json_decode($result, true);
        $responseText = $json["response"] ?? "No response from model.";
    }

    curl_close($ch);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DeepSeek Chat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="chat-container">
    <h1>DeepSeek Chat</h1>
    <form method="post">
        <textarea name="prompt" placeholder="Type your prompt..." required><?php echo htmlspecialchars($_POST["prompt"] ?? ""); ?></textarea>
        <button type="submit">Ask</button>
    </form>

    <?php if (!empty($responseText)): ?>
        <div class="response">
            <h2>Response:</h2>
            <p><?php echo nl2br(htmlspecialchars($responseText)); ?></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
