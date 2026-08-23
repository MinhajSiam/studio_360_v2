<?php
// আপনার ডাটাবেসের তথ্যগুলো এখানে দিন
$host = 'localhost';
$dbname = 'innovat2_studio360_models';
$user = 'innovat2_admin';
$pass = 'MinhajSiam007%';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS registered_models (
        id INT AUTO_INCREMENT PRIMARY KEY,
        folder_name VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        gender VARCHAR(20),
        age VARCHAR(10),
        phone VARCHAR(20),
        address TEXT,
        reference VARCHAR(100),
        height VARCHAR(20),
        hair VARCHAR(50),
        experience VARCHAR(20),
        insta_link VARCHAR(255),
        fb_link VARCHAR(255),
        drive_link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    // যদি আগে থেকেই টেবিল থাকে, তবে gender কলামটি অ্যাড করে নেবে
    try {
        $pdo->exec("ALTER TABLE registered_models ADD COLUMN gender VARCHAR(20) AFTER name");
    } catch (PDOException $e) {
        // কলাম আগে থেকেই থাকলে এরর ইগনোর করবে
    }
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
