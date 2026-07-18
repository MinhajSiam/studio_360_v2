<?php
// আপনার ডাটাবেসের তথ্যগুলো এখানে দিন
$host = 'localhost'; 
$dbname = 'innovat2_studio360_models'; 
$user = 'innovat2_admin'; 
$pass = 'MinhajSiam007%';

try {
    // ডাটাবেস কানেকশন (PDO)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // অটোমেটিক টেবিল তৈরি করার কোড (যদি আগে থেকে না থাকে)
    $sql = "CREATE TABLE IF NOT EXISTS registered_models (
        id INT AUTO_INCREMENT PRIMARY KEY,
        folder_name VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
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

} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>