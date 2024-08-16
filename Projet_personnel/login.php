<?php
session_start();
require_once 'db.php';

$db = dbConnect();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the user exists in the database
    $stmt = $db->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE nom_utilisateur = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        // If the password is correct, set the session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom_utilisateur'] = $username;

        // Redirect to the category page
        header('Location: category.php');
        exit();
    } else {
        // If the login fails, show an error message
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #10b740;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #10b740;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        button[type="submit"]:hover {
            background-color: #0a6d30;
        }

        .error {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }

        .home-button {
            text-align: center;
            margin-top: 10px;
        }

        .home-button a {
            display: inline-block;
            padding: 10px;
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
        }

        .home-button a:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="home-button">
        <a href="index.php">Return to Homepage</a>
    </div>
</body>
</html>
