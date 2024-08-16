<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
            max-width: 400px;
            margin: 50px auto;
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
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            padding: 10px;
            background-color: #10b740;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
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
    <h1>Sign Up</h1>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label for="nom_utilisateur">Username</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>

        <label for="mot_de_passe">Password</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <label for="mot_de_passe_conf">Confirm Password</label>
        <input type="password" id="mot_de_passe_conf" name="mot_de_passe_conf" required>

        <button type="submit">Sign Up</button>
    </form>

    <div class="home-button">
        <a href="index.php">Return to Homepage</a>
    </div>
</body>
</html>
