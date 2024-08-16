<?php
session_start();
require_once 'db.php';

$db = dbConnect();

$username = isset($_SESSION['nom_utilisateur']) ? $_SESSION['nom_utilisateur'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedCategories = isset($_POST['categories']) ? $_POST['categories'] : [];
    $_SESSION['selectedCategories'] = $selectedCategories;
    $_SESSION['currentQuestionIndex'] = 0;  
    header('Location: questions.php');
    exit();
}

try {
    $categoriesStmt = $db->query("SELECT * FROM product_categories");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}

if (!$categories) {
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Categories </title>
    <style>
        body, h1, h2, p, ul, li, form, input, button {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font-family: 'Arial', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            color: #09202b;
            line-height: 1.6;
            font-size: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        header {
            width: 100%;
            display: flex;
            justify-content: space-between;
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5; 
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        h1, h2 {
            color: #09202b; 
            margin-bottom: 10px;
            font-weight: normal; 
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
        }

        form {
            margin: 0 auto;
            border-radius: 8px;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .category-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            max-width: 100%;
        }

        .category-button {
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

        .category-button.selected,
        .category-button:hover {
            background-color: #10b740; 
            color: #ffffff; 
            transform: scale(1.05);
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: #10b740; /* Button background color */
            color: #ffffff; /* Button text color */
            border: 2px solid #10b740; /* Button border color */
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em; /* Reduced font size */
            transition: background-color 0.3s, transform 0.3s;
            text-transform: uppercase;
            font-weight: bold; /* Bold text */
        }

        button[type="submit"]:hover {
            background-color: #0a6d30; /* Button hover background color */
            color: #ffffff; /* Button text color on hover */
            transform: scale(1.02);
        }

        button[type="submit"]:active {
            background-color: #0a6d30; /* Button active background color */
            border-color: #0a6d30; /* Button active border color */
            color: #ffffff; /* Button text color on active */
        }

        input[type="checkbox"] {
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            form {
                padding: 10px;
                width: 90%; 
            }

            .category-group {
                flex-direction: column; 
                align-items: flex-start;
                width: 100%; 
            }

            .category-button {
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
            .category-button {
                padding: 8px 10px;
                font-size: 0.8em; 
            }

            button[type="submit"] {
                padding: 8px;
                font-size: 0.8em; 
            }
        }
    </style>
    <script>
        function toggleCategory(button) {
            button.classList.toggle('selected');
            var input = document.getElementById('category-' + button.dataset.categoryId);
            input.checked = !input.checked;
        }
    </script>
</head>
<body>
    <header>
        <div>&#9776;</div>
        <?php if ($username): ?>
            <div class="user-menu">
                <button><?php echo htmlspecialchars($username); ?></button>
                <div class="user-menu-content">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </header>
    <main class="container">
        <h1>Select Your Health Focus Areas</h1>
        <p>Choose one or more categories that best match your current health goals to receive customized supplement advice.</p>
        <form method="POST" class="category-form">
            <div class="category-group">
                <?php foreach ($categories as $category): ?>
                    <div class="category-button" onclick="toggleCategory(this)" data-category-id="<?php echo $category['category_id']; ?>">
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </div>
                    <input type="checkbox" name="categories[]" id="category-<?php echo $category['category_id']; ?>" value="<?php echo $category['category_id']; ?>">
                <?php endforeach; ?>
            </div>
            <button type="submit">Select My Categories</button>
        </form>
    </main>
</body>
</html>
