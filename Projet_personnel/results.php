<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['answers']) || !isset($_SESSION['selectedCategories'])) {
    header('Location: index.php');
    exit();
}

$answers = $_SESSION['answers'];
$selectedCategories = $_SESSION['selectedCategories'];

$db = dbConnect();

$placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
$questionsStmt = $db->prepare("
    SELECT q.id, q.question, q.parent_id FROM questions q
    WHERE q.parent_id IN ($placeholders)
");
$questionsStmt->execute($selectedCategories);
$questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC);

function getAIRecommendations($answers, $selectedCategories, $questions) {
    $apiUrl = 'https://api.openai.com/v1/chat/completions';
    $apiKey = 'sk-proj-cantsharethis'; 

    $db = dbConnect();

    $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
    $productsStmt = $db->prepare("
        SELECT p.name FROM product p
        WHERE p.category_id IN ($placeholders)
    ");
    $productsStmt->execute($selectedCategories);
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions) || empty($products)) {
        return "No questions or products available for the selected categories.";
    }

    $userData = [];
    foreach ($questions as $question) {
        if (isset($answers[$question['id']])) {
            $userData[] = $question['question'] . ": " . $answers[$question['id']];
        }
    }

    $productsString = implode(', ', array_map(function($product) {
        return $product['name'];
    }, $products));

    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'system',
                'content' => "You are an expert in nutritional supplements and health optimization. Based on the user's responses and the selected categories, provide between 3 to 10 personalized supplement recommendations. For each recommendation, clearly explain why it is suitable for the user by linking it to their specific responses. Focus on the user's needs and preferences, and avoid unrelated information. Only consider the following supplements: $productsString."
            ],
            [
                'role' => 'user',
                'content' => 'The user has answered the following: ' . implode(', ', $userData)
            ]
        ],
        'max_tokens' => 1500,
        'temperature' => 0.7,
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
        'ssl' => [
            'cafile' => 'C:\wamp64\bin\php\cacert.pem',
            'verify_peer' => true,
            'verify_peer_name' => true,
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        return 'An error occurred while processing your request: ' . $error['message'];
    }

    $response = json_decode($result, true);
    if (isset($response['error'])) {
        return 'An error occurred: ' . $response['error']['message'];
    }

    $recommendationText = $response['choices'][0]['message']['content'];
    return nl2br($recommendationText);
}

$recommendations = getAIRecommendations($answers, $selectedCategories, $questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplement Recommendations</title>
    <style>
        @font-face {
            font-family: "apercu-pro";
            font-weight: 400; 
            font-style: normal;
            font-display: swap;
            src: url("./fonts/experiments/ApercuPro-Regular-latin.woff2") format("woff2"),
                 url("./fonts/experiments/ApercuPro-Regular-latin.woff") format("woff"),
                 url("./fonts/experiments/ApercuPro-Regular-latin.ttf") format("truetype");
            unicode-range: U+0000-00FF;
        }

        body, h1, h2, p, ul, li, a, div {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font-family: 'apercu-pro', 'Arial', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff; 
            color: #09202b; 
            line-height: 1.6;
            font-size: 16px;
            overflow: auto; 
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        header {
            width: 100%;
            display: flex;
            justify-content: center; 
            padding: 20px;
        }

        header img {
            height: 120px; 
        }

        #results-content {
            max-width: 800px;
            padding: 20px;
            background: #f5f5f5; 
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: left;
        }

        h1 {
            color: #10b740; 
            margin-bottom: 20px;
            font-family: 'apercu-pro', 'Georgia', serif;
            font-size: 2em;
            text-transform: uppercase;
            font-weight: normal; 
            text-align: center;
        }

        h2 {
            color: #09202b; 
            margin-bottom: 10px;
            font-family: 'apercu-pro', 'Georgia', serif;
            font-size: 1.5em;
            text-transform: uppercase;
            font-weight: normal; 
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-bottom: 20px;
        }

        ul li {
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .recommendation p {
            color: #09202b; 
            font-size: 1.2em;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        a.start-over {
            display: inline-block;
            padding: 10px 15px;
            margin: 20px 0;
            border: 2px solid #10b740; 
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
            text-align: center;
            user-select: none;
            font-weight: bold; 
            font-size: 1em; 
            color: #10b740; 
            text-decoration: none;
            text-transform: uppercase;
        }

        a.start-over:hover {
            background-color: #10b740; 
            color: #ffffff; 
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            #results-content {
                padding: 10px;
            }

            a.start-over {
                padding: 10px;
                font-size: 0.9em; 
            }
        }

        @media (max-width: 480px) {
            a.start-over {
                padding: 8px;
                font-size: 0.8em; 
            }
        }
    </style>
</head>
<body>
    <header>
    </header>

    <div id="results-content">
        <h1>Your Supplement Recommendations</h1>

        <h2>Your Responses:</h2>
        <ul>
            <?php foreach ($questions as $question): ?>
                <?php if (isset($answers[$question['id']])): ?>
                    <li><?php echo htmlspecialchars($question['question']); ?>: <?php echo htmlspecialchars($answers[$question['id']]); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <h2>Recommendations:</h2>
        <div class="recommendation">
            <p><?php echo $recommendations; ?></p>
        </div>

        <a href="index.php" class="start-over">Start Over</a>
    </div>
</body>
</html>
