<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projet Personnel ESIG, Quizz nutrition</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #09202b;
            background-color: #ffffff;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .action-button {
            padding: 15px 30px;
            font-size: 1.2rem;
            cursor: pointer;
            background-color: #10b740;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
        }

        .action-button:hover {
            background-color: #0a6d30;
        }

        @media (max-width: 780px) {
            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 1.2rem;
            }

            .action-button {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Which supplement is right for you?</h1>
        <p>We’ll find the best supplement for you with this easy quiz.</p>
        <div class="button-container">
            <a href="login.php"><button type="button" class="action-button">Login</button></a>
            <a href="inscription.php"><button type="button" class="action-button">Sign Up</button></a>
        </div>
    </div>
</body>
</html>
