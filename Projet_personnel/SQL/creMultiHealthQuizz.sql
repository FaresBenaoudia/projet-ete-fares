-- Create product categories table
CREATE TABLE product_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create questions table
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    type ENUM('texte', 'nombre', 'oui_non', 'choix_multiple') NOT NULL,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES product_categories(category_id) ON DELETE CASCADE,
    INDEX (parent_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create choices table
CREATE TABLE choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    choice TEXT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX (question_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create product table
CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES product_categories(category_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create utilisateurs table
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(191) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create reponses table
CREATE TABLE reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,  
    question_id INT, 
    choix_id INT DEFAULT NULL,  
    reponse_texte TEXT DEFAULT NULL, 
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (choix_id) REFERENCES choices(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
