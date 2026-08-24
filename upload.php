<?php
header('Content-Type: application/json');
require 'config.php';

$baseDir = 'uploads/';
if (!file_exists($baseDir)) mkdir($baseDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['name'] ?? 'Unknown');
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '000');
    $folderName = trim($name) . '_' . $phone . '_' . time();
    $folderName = str_replace(' ', '_', $folderName);

    $targetDir = $baseDir . $folderName . '/';
    mkdir($targetDir, 0777, true);

    try {
        $stmt = $pdo->prepare("INSERT INTO registered_models (folder_name, name, gender, age, phone, address, reference, height, weight, experience, insta_link, fb_link, drive_link, tiktok_link, rem_whole_day, rem_per_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $folderName,
            $_POST['name'] ?? '',
            $_POST['gender'] ?? '',
            $_POST['age'] ?? '',
            $_POST['phone'] ?? '',
            $_POST['address'] ?? '',
            $_POST['reference'] ?? '',
            $_POST['height'] ?? '',
            $_POST['weight'] ?? '',
            $_POST['experience'] ?? '',
            $_POST['instaLink'] ?? '',
            $_POST['fbLink'] ?? '',
            $_POST['driveLink'] ?? '',
            $_POST['tiktokLink'] ?? '',
            $_POST['remWholeDay'] ?? '',
            $_POST['remPerContent'] ?? ''
        ]);

        function uploadFiles($fileInputName, $prefix, $targetDir)
        {
            if (isset($_FILES[$fileInputName])) {
                $total = count($_FILES[$fileInputName]['name']);
                for ($i = 0; $i < $total; $i++) {
                    if ($_FILES[$fileInputName]['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES[$fileInputName]['name'][$i], PATHINFO_EXTENSION);
                        $newFileName = $prefix . '_' . uniqid() . '.' . strtolower($ext);
                        move_uploaded_file($_FILES[$fileInputName]['tmp_name'][$i], $targetDir . $newFileName);
                    }
                }
            }
        }

        uploadFiles('casualPhotos', 'casual', $targetDir);
        uploadFiles('portfolioPhotos', 'portfolio', $targetDir);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
