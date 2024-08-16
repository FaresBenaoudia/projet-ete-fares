-- Insert product categories (excluding "Reproductive and Hormonal Support" and "Joint and Bone Support")
INSERT INTO product_categories (category_name) VALUES 
('Energy and Performance'),
('Hydration'),
('Sports Performance'),
('Sleep and Relaxation'),
('Health and Wellness'),
('Cognitive Support'),
('Skin Care and Anti-Aging'),
('Metabolic and Digestive Support');

-- Sleep and Relaxation Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Do you have trouble falling asleep?', 'oui_non', 4),
('How long does it take you to fall asleep on average?', 'choix_multiple', 4),
('Do you wake up frequently during the night?', 'oui_non', 4),
('How many times do you wake up during the night on average?', 'choix_multiple', 4),
('What typically causes you to wake up?', 'choix_multiple', 4),
('Do you feel rested when you wake up in the morning?', 'oui_non', 4),
('How many hours of sleep do you get on average per night?', 'choix_multiple', 4);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Less than 15 minutes'),
(LAST_INSERT_ID(), '15-30 minutes'),
(LAST_INSERT_ID(), 'More than 30 minutes'),
(LAST_INSERT_ID(), '1-2 times'),
(LAST_INSERT_ID(), '3-4 times'),
(LAST_INSERT_ID(), 'More than 4 times'),
(LAST_INSERT_ID(), 'Noise'),
(LAST_INSERT_ID(), 'Stress'),
(LAST_INSERT_ID(), 'Other'),
(LAST_INSERT_ID(), 'Less than 6 hours'),
(LAST_INSERT_ID(), '6-8 hours'),
(LAST_INSERT_ID(), 'More than 8 hours');

COMMIT;

-- Energy and Performance Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Do you often feel mentally fatigued?', 'oui_non', 1),
('Do you have trouble concentrating?', 'oui_non', 1),
('How often do you feel mentally fatigued?', 'choix_multiple', 1),
('Do you consume caffeine regularly?', 'oui_non', 1),
('Do you find it hard to stay motivated?', 'oui_non', 1),
('Do you get enough physical exercise?', 'oui_non', 1),
('What kind of exercise do you engage in?', 'choix_multiple', 1);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Rarely'),
(LAST_INSERT_ID(), 'Sometimes'),
(LAST_INSERT_ID(), 'Often'),
(LAST_INSERT_ID(), 'Cardio'),
(LAST_INSERT_ID(), 'Strength training'),
(LAST_INSERT_ID(), 'Mixed');

COMMIT;

-- Hydration Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Do you experience muscle cramps frequently?', 'oui_non', 2),
('Do you find it hard to stay hydrated?', 'oui_non', 2),
('Do you often feel tired or lethargic?', 'oui_non', 2),
('How many glasses of water do you drink per day?', 'choix_multiple', 2),
('Do you consume electrolyte drinks?', 'oui_non', 2),
('Do you sweat excessively during exercise?', 'oui_non', 2),
('Do you feel dizzy or lightheaded often?', 'oui_non', 2);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Less than 4 glasses'),
(LAST_INSERT_ID(), '4-6 glasses'),
(LAST_INSERT_ID(), '7-9 glasses'),
(LAST_INSERT_ID(), '10 or more glasses');

COMMIT;

-- Sports Performance Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Are you looking to increase your muscle mass?', 'oui_non', 3),
('Do you want to enhance your endurance?', 'oui_non', 3),
('Do you experience muscle soreness after workouts?', 'oui_non', 3),
('How many days a week do you workout?', 'choix_multiple', 3),
('What is your primary goal?', 'choix_multiple', 3),
('How long does muscle soreness last after workouts?', 'choix_multiple', 3),
('Do you use any performance supplements currently?', 'oui_non', 3);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), '1-2 days'),
(LAST_INSERT_ID(), '3-4 days'),
(LAST_INSERT_ID(), '5 or more days'),
(LAST_INSERT_ID(), 'Building muscle'),
(LAST_INSERT_ID(), 'Improving endurance'),
(LAST_INSERT_ID(), 'Both'),
(LAST_INSERT_ID(), '1-2 days'),
(LAST_INSERT_ID(), '3-4 days'),
(LAST_INSERT_ID(), 'More than 4 days');

COMMIT;

-- Health and Wellness Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Are you looking to boost your immune system?', 'oui_non', 5),
('Do you want to improve your cardiovascular health?', 'oui_non', 5),
('Are you interested in antioxidants to fight free radicals?', 'oui_non', 5),
('Do you get sick frequently?', 'oui_non', 5),
('Do you consume a diet rich in fruits and vegetables?', 'oui_non', 5),
('Do you exercise regularly?', 'oui_non', 5),
('Do you experience high levels of stress?', 'oui_non', 5);

COMMIT;

-- Cognitive Support Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Are you looking to improve your memory?', 'oui_non', 6),
('Do you want to enhance your mental focus?', 'oui_non', 6),
('Are you looking for ways to reduce mental stress?', 'oui_non', 6),
('Do you find it hard to remember things?', 'oui_non', 6),
('Do you feel your focus diminishes as the day progresses?', 'oui_non', 6),
('How would you rate your stress levels?', 'choix_multiple', 6),
('Do you use any cognitive supplements currently?', 'oui_non', 6);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Low'),
(LAST_INSERT_ID(), 'Moderate'),
(LAST_INSERT_ID(), 'High');

COMMIT;

-- Skin Care and Anti-Aging Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Are you concerned about skin aging and wrinkles?', 'oui_non', 7),
('Do you want to improve your skin elasticity?', 'oui_non', 7),
('Are you looking to improve your skin hydration?', 'oui_non', 7),
('Do you use anti-aging skincare products?', 'oui_non', 7),
('Are you exposed to the sun frequently?', 'oui_non', 7),
('Do you have dry or oily skin?', 'choix_multiple', 7),
('Do you have any specific skin conditions?', 'oui_non', 7);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Dry'),
(LAST_INSERT_ID(), 'Oily'),
(LAST_INSERT_ID(), 'Combination');

COMMIT;

-- Metabolic and Digestive Support Questions and Choices
START TRANSACTION;

INSERT INTO questions (question, type, parent_id) VALUES
('Are you looking to manage your weight?', 'oui_non', 8),
('Do you experience digestive issues?', 'oui_non', 8),
('Are you interested in improving your metabolic health?', 'oui_non', 8),
('Are you on any specific diet?', 'oui_non', 8),
('How often do you exercise?', 'choix_multiple', 8),
('Do you experience bloating or indigestion frequently?', 'oui_non', 8),
('Do you monitor your blood sugar levels?', 'oui_non', 8);

INSERT INTO choices (question_id, choice) VALUES
(LAST_INSERT_ID(), 'Daily'),
(LAST_INSERT_ID(), 'Weekly'),
(LAST_INSERT_ID(), 'Occasionally');

COMMIT;

-- Insert products with correct category_ids (excluding those related to "Reproductive and Hormonal Support" and "Joint and Bone Support")
INSERT INTO product (name, category_id) 
VALUES
('Taurine Powder', 1),
('D-Ribose Powder', 1),
('Guarana Extract', 1),
('Caffeine Capsules', 1),
('Dandelion Extract Powder', 1),
('Magnesium Glycinate Powder', 2),
('Magnesium Citrate Powder', 2),
('Potassium Chloride Powder', 2),
('Potassium Citrate Powder', 2),
('Micronized Creatine Monohydrate Powder', 3),
('Creatine Monohydrate Capsules', 3),
('Beta-Alanine Powder', 3),
('L-Glutamine Powder', 3),
('AAKG (L-Arginine Alpha-Ketoglutarate) Powder', 3),
('L-Arginine Powder', 3),
('L-Carnitine Powder', 3),
('ALCAR HCl (Acetyl L-Carnitine HCl) Powder', 3),
('90% Whey Protein Isolate Powder', 3),
('Pea Protein Powder', 3),
('Grass-Fed Whey Protein Concentrate Powder', 3),
('80% Whey Protein Concentrate Powder', 3),
('Hydrolyzed Whey Protein Isolate Powder', 3),
('BCAA 2:1:1 (Branched-Chain Amino Acids) Powder', 3),
('BCAA 3:1:2 (Branched-Chain Amino Acids) Powder', 3),
('Essential Amino Acids (EAA) Powder', 3),
('Essential Amino Acids (EAA) Capsules', 3),
('L-Leucine Powder', 3),
('L-Theanine Powder', 4),
('L-Theanine Capsules', 4),
('Melatonin Powder', 4),
('Glycine Powder', 4),
('GABA Powder', 4),
('Rhodiola Extract Powder (3% salidroside)', 4),
('Ascorbic Acid (Vitamin C) Powder', 5),
('Vitamin D3 + K2 Capsules', 5),
('Vitamin D3 Capsules', 5),
('1% Methylcobalamin (Vitamin B12) Powder', 5),
('98% Pure Resveratrol Powder', 5),
('Resveratrol Capsules', 5),
('Reduced Glutathione Powder', 5),
('NMN (Nicotinamide Mononucleotide) Powder', 5),
('NMN Capsules', 5),
('Organic Spirulina Powder', 5),
('Milk Thistle Extract Powder', 5),
('Alpha GPC (L-Alpha Glycerylphosphorylcholine) Powder', 6),
('L-Tyrosine Powder', 6),
('Organic Lion\'s Mane Powder', 6),
('Organic Lion\'s Mane Mushroom Extract Powder', 6),
('Lion\'s Mane Mushroom Extract Capsules', 6),
('Cordyceps Extract Powder', 6),
('Reishi Mushroom Extract Powder', 6),
('Turkey Tail Mushroom Extract Powder', 6),
('N-Acetyl L-Cysteine (NAC) Powder', 6),
('N-Acetyl-L-Cysteine (NAC) Capsules', 6),
('Collagen Peptides Powder', 7),
('Hydrolyzed Collagen (Fish) Powder', 7),
('Hydrolyzed Collagen (Chicken) Powder', 7),
('Chondroitin Sulfate Powder', 7),
('Niacinamide (Vitamin B3) Powder', 7),
('Niacin (Vitamin B3) Powder', 7),
('Niacin Capsules', 7),
('Hyaluronic Acid (Sodium Hyaluronate) Powder', 7),
('Shilajit Extract Powder', 7);

COMMIT;
