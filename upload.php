<?php
header('Content-Type: application/json');

// মেইন ফোল্ডার তৈরি (যদি না থাকে)
$baseDir = 'uploads/';
if (!file_exists($baseDir)) {
    mkdir($baseDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ইউজারের নাম ও ফোন নম্বর দিয়ে ফোল্ডারের নাম তৈরি
    $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['name'] ?? 'Unknown');
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '000');
    $folderName = trim($name) . '_' . $phone . '_' . time(); // time() দেওয়া হলো যাতে নাম ডুপ্লিকেট না হয়
    $folderName = str_replace(' ', '_', $folderName);
    
    $targetDir = $baseDir . $folderName . '/';
    mkdir($targetDir, 0777, true);

    // ইউজারের টেক্সট ডেটাগুলো একটি ফাইলে সেভ করে রাখা
    $details = "Name: " . ($_POST['name'] ?? '') . "\n";
    $details .= "Age: " . ($_POST['age'] ?? '') . "\n";
    $details .= "Phone: " . ($_POST['phone'] ?? '') . "\n";
    $details .= "Address: " . ($_POST['address'] ?? '') . "\n";
    $details .= "Reference: " . ($_POST['reference'] ?? '') . "\n";
    $details .= "Height: " . ($_POST['height'] ?? '') . "\n";
    $details .= "Hair: " . ($_POST['hair'] ?? '') . "\n";
    $details .= "Experience: " . ($_POST['experience'] ?? '') . "\n";
    $details .= "Insta Link: " . ($_POST['instaLink'] ?? '') . "\n";
    $details .= "FB Link: " . ($_POST['fbLink'] ?? '') . "\n";
    $details .= "Drive Link: " . ($_POST['driveLink'] ?? '') . "\n";
    
    file_put_contents($targetDir . 'details.txt', $details);

    // ছবি আপলোড করার ফাংশন
    function uploadFiles($fileInputName, $prefix, $targetDir) {
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

    // Casual এবং Portfolio ছবিগুলো ফোল্ডারে সেভ করা
    uploadFiles('casualPhotos', 'casual', $targetDir);
    uploadFiles('portfolioPhotos', 'portfolio', $targetDir);

    echo json_encode(['status' => 'success', 'folder' => $folderName]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>