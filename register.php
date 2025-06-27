<?php
header("Content-Type: application/json");

// Get JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Neon credentials
$host = 'ep-bitter-violet-a1ija7b4-pooler.ap-southeast-1.aws.neon.tech';
$db = 'user';
$user = 'user_owner';
$pass = 'npg_jBkWc71zHKCE';
$sslmode = 'require';

// User fields
$fname = $data['fname'];
$lname = $data['lname'];
$contact = $data['contact'];
$email = $data['email'];
$password = $data['password'];

// Generate user_id
$date = date('Ymd');
$unique = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
$user_id = "U{$date}-{$unique}";

// Connect to Neon
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db;sslmode=$sslmode", $user, $pass);
    $stmt = $pdo->prepare("INSERT INTO users (user_id, first_name, last_name, contact_num, email, password)
                           VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $user_id,
        $fname,
        $lname,
        $contact,
        $email,
        $password // In production, use password_hash
    ]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
