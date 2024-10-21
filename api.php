<?php
// Database configuration
$host = 'localhost'; 
$dbname = 'complaints'; 
$username = 'root'; 
$password = ''; 

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle POST request (register a complaint)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("INSERT INTO complaints (title, description, category) VALUES (:title, :description, :category)");
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':category' => $data['category']
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Complaint registered successfully.']);
        exit();
    }

    // Handle GET request (fetch complaints)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT id, title, description, category, status, created_at FROM complaints ORDER BY created_at DESC");
        $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($complaints);
        exit();
    }

    // Handle PUT request (update complaint status)
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        $stmt = $pdo->prepare("UPDATE complaints SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $data['status'],
            ':id' => $data['id']
        ]);

        echo json_encode(['message' => 'Complaint status updated successfully.']);
        exit();
    }

} catch (PDOException $e) {
    http_response_code(500); 
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>