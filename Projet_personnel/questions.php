<?php
session_start();
require_once 'db.php';

$db = dbConnect();

$selectedCategories = isset($_SESSION['selectedCategories']) ? $_SESSION['selectedCategories'] : [];
$answers = isset($_SESSION['answers']) ? $_SESSION['answers'] : [];
$currentQuestionIndex = isset($_SESSION['currentQuestionIndex']) ? $_SESSION['currentQuestionIndex'] : 0;
$username = isset($_SESSION['nom_utilisateur']) ? $_SESSION['nom_utilisateur'] : null;

if (empty($selectedCategories)) {
    header('Location: category.php');
    exit();
}

$placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
$questionsStmt = $db->prepare("
    SELECT q.* FROM questions q
    WHERE q.parent_id IN ($placeholders)
");
$questionsStmt->execute($selectedCategories);
$questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    echo "No questions available for the selected categories.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $questionId = $_POST['question_id'];
        $answers[$questionId] = $_POST['answer'];
        $_SESSION['answers'] = $answers;

        $_SESSION['currentQuestionIndex']++;

        // Redirect to verifi_reponse.php if all questions are answered
        if ($_SESSION['currentQuestionIndex'] >= count($questions)) {
            header('Location: verifi_reponse.php');
            exit();
        } else {
            header('Location: questions.php');
            exit();
        }
    }
}

$currentQuestion = $questions[$currentQuestionIndex];

$choicesStmt = $db->prepare("SELECT * FROM choices WHERE question_id = ?");
$choicesStmt->execute([$currentQuestion['id']]);
$choices = $choicesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Questions</title>
    <style>
        body, h1, p, form, input, button {
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
            height: 100vh;
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
        }

        header img {
            height: 150px;
            width: auto;
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
            color: #09202b;
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
            color: #09202b;
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

        #quiz-content {
            max-width: 800px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        p.question-text {
            color: #09202b;
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #10b740;
            border-radius: 5px;
            font-size: 1em;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            background-color: #10b740;
            color: #ffffff;
            border: 2px solid #10b740;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
            text-transform: uppercase;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #0a6d30;
            transform: scale(1.02);
        }

        button[type="submit"]:active {
            background-color: #0a6d30;
            border-color: #0a6d30;
        }

        .choice-button {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border: 2px solid #10b740;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
            text-align: center;
            user-select: none;
            font-weight: bold;
            font-size: 0.9em;
            color: #10b740;
        }

        .choice-button.selected,
        .choice-button:hover {
            background-color: #10b740;
            color: #ffffff;
            transform: scale(1.05);
        }

        .choice-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            #quiz-content {
                padding: 10px;
            }

            .choice-button {
                width: calc(100% - 20px);
                text-align: left;
                padding: 10px;
            }

            button[type="submit"] {
                padding: 10px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .choice-button {
                padding: 8px 10px;
                font-size: 0.8em;
            }

            button[type="submit"] {
                padding: 8px;
                font-size: 0.8em;
            }
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


    <div id="quiz-content">
        <p class="question-text"><?php echo htmlspecialchars($currentQuestion['question']); ?></p>
        <form method="POST" onsubmit="showLoadingScreen()">
            <input type="hidden" name="question_id" value="<?php echo $currentQuestion['id']; ?>">

            <?php if ($currentQuestion['type'] == 'texte'): ?>
                <input type="text" name="answer" required>
            <?php elseif ($currentQuestion['type'] == 'nombre'): ?>
                <input type="number" name="answer" required>
            <?php elseif ($currentQuestion['type'] == 'oui_non'): ?>
                <button type="submit" name="answer" value="yes" class="choice-button">Yes</button>
                <button type="submit" name="answer" value="no" class="choice-button">No</button>
            <?php elseif ($currentQuestion['type'] == 'choix_multiple' && !empty($choices)): ?>
                <div class="choice-container">
                    <?php foreach ($choices as $choice): ?>
                        <button type="submit" name="answer" value="<?php echo htmlspecialchars($choice['choice']); ?>" class="choice-button"><?php echo htmlspecialchars($choice['choice']); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
