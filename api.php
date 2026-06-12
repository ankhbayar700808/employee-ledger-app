<?php
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    // --- АЖИЛЧИДТАЙ ХОЛБООТОЙ ҮЙЛДЛҮҮД ---
    if ($action === 'employees') {
        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM employees ORDER BY name ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } 
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!empty($data['name'])) {
                if (!empty($data['id'])) {
                    // АЖИЛТАН ЗАСАХ (UPDATE)
                    $stmt = $pdo->prepare("UPDATE employees SET name = ?, position = ? WHERE id = ?");
                    $stmt->execute([$data['name'], $data['position'] ?? '', $data['id']]);
                    echo json_encode(['status' => 'success', 'message' => 'Ажилтны мэдээлэл шинэчлэгдлээ']);
                } else {
                    // ШИНЭ АЖИЛТАН НЭМЭХ (INSERT)
                    $stmt = $pdo->prepare("INSERT INTO employees (name, position) VALUES (?, ?)");
                    $stmt->execute([$data['name'], $data['position'] ?? '']);
                    echo json_encode(['status' => 'success', 'message' => 'Ажилтан нэмэгдлээ']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Нэр оруулна уу']);
            }
        }
    } 
    elseif ($action === 'delete_employee') {
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!empty($data['id'])) {
                $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
                $stmt->execute([$data['id']]);
                echo json_encode(['status' => 'success', 'message' => 'Ажилтан shadow-deleted']);
            }
        }
    }
    
    // --- ГҮЙЛГЭЭТЭЙ ХОЛБООТОЙ ҮЙЛДЛҮҮД ---
    else {
        if ($method === 'GET') {
            $query = "SELECT t.*, e.name as employee_name 
                      FROM transactions t 
                      JOIN employees e ON t.employee_id = e.id 
                      ORDER BY t.date DESC, t.id DESC";
            $stmt = $pdo->query($query);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } 
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!empty($data['employee_id']) && !empty($data['date']) && !empty($data['description']) && !empty($data['type']) && !empty($data['amount'])) {
                
                if (!empty($data['id'])) {
                    // ГҮЙЛГЭЭ ЗАСАХ (UPDATE)
                    $stmt = $pdo->prepare("UPDATE transactions SET employee_id = ?, date = ?, description = ?, type = ?, amount = ? WHERE id = ?");
                    $stmt->execute([$data['employee_id'], $data['date'], $data['description'], $data['type'], $data['amount'], $data['id']]);
                    echo json_encode(['status' => 'success', 'message' => 'Гүйлгээ шинэчлэгдлээ']);
                } else {
                    // ШИНЭ ГҮЙЛГЭЭ НЭМЭХ (INSERT)
                    $stmt = $pdo->prepare("INSERT INTO transactions (employee_id, date, description, type, amount) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$data['employee_id'], $data['date'], $data['description'], $data['type'], $data['amount']]);
                    echo json_encode(['status' => 'success', 'message' => 'Гүйлгээ бүртгэгдлээ']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Мэдээлэл дутуу байна']);
            }
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
