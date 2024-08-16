<?php
session_start();
require_once 'db.php';

$db = dbConnect();

if (!isset($_SESSION['selectedCategories']) || !isset($_SESSION['answers'])) {
    echo "No answers to display.";
    exit;
}

$selectedCategories = $_SESSION['selectedCategories'];
$answers = $_SESSION['answers'];
$questions = [];

$placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
$stmt = $db->prepare("
    SELECT q.id, q.question 
    FROM questions q 
    WHERE q.parent_id IN ($placeholders)
");
$stmt->execute($selectedCategories);
$allQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($allQuestions as $question) {
    if (isset($answers[$question['id']])) {
        $questions[$question['id']] = $question['question'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($userId === null) {
        echo "User not logged in.";
        exit;
    }

    foreach ($_POST['answers'] as $questionId => $newAnswer) {
        $_SESSION['answers'][$questionId] = $newAnswer;

        // Check if the answer is a choice from a predefined list
        $choiceStmt = $db->prepare("SELECT id FROM choices WHERE question_id = ? AND choice = ?");
        $choiceStmt->execute([$questionId, $newAnswer]);
        $choice = $choiceStmt->fetch(PDO::FETCH_ASSOC);
        $choixId = $choice ? $choice['id'] : null;

        // Insert or update the response
        $insertStmt = $db->prepare("
            INSERT INTO reponses (utilisateur_id, question_id, choix_id, reponse_texte)
            VALUES (:utilisateur_id, :question_id, :choix_id, :reponse_texte)
            ON DUPLICATE KEY UPDATE choix_id = :choix_id, reponse_texte = :reponse_texte
        ");
        $insertStmt->execute([
            'utilisateur_id' => $userId,
            'question_id' => $questionId,
            'choix_id' => $choixId,
            'reponse_texte' => $choixId ? null : $newAnswer,
        ]);
    }
    
    header('Location: results.php');
    exit();
}

$username = isset($_SESSION['nom_utilisateur']) ? $_SESSION['nom_utilisateur'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify and Modify Your Responses</title>
    <style>
        body, h1, p, form, input, button {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        header {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 20px;
            background-color: #f5f5f5;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-menu button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }

        .user-menu button:focus {
            outline: none;
        }

        .user-menu-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f5f5f5;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 5px;
            text-align: left;
        }

        .user-menu-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .user-menu-content a:hover {
            background-color: #ddd;
        }

        .user-menu:hover .user-menu-content {
            display: block;
        }

        h1 {
            text-align: center;
            color: #10b740;
            margin-top: 100px;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .question-container {
            margin-bottom: 20px;
        }

        .question-container p {
            font-weight: bold;
        }

        .answer-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        button {
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #10b740;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <?php if ($username): ?>
            <div class="user-menu">
                <button><?php echo htmlspecialchars($username); ?></button>
                <div class="user-menu-content">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <h1>Verify and Modify Your Responses</h1>
    <form method="POST">
        <?php foreach ($questions as $questionId => $questionText): ?>
            <div class="question-container">
                <p><?php echo htmlspecialchars($questionText); ?></p>
                <input type="text" name="answers[<?php echo $questionId; ?>]" class="answer-input" value="<?php echo htmlspecialchars($answers[$questionId]); ?>">
            </div>
        <?php endforeach; ?>
        <button type="submit">Confirm</button>
    </form>
</body>
</html>
