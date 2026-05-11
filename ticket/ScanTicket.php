<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$qr_code = trim($data['qr_code'] ?? '');

if (empty($qr_code)) {
    echo json_encode([
        'success' => false,
        'message' => 'QR Code is empty'
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, scanned FROM tickets_tb WHERE ticket_id = ? LIMIT 1");
    $stmt->execute([$qr_code]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo json_encode([
            'success' => false,
            'message' => 'Ticket not found'
        ]);
        exit;
    }

    if ($ticket['scanned'] == 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Ticket already scanned'
        ]);
        exit;
    }

    // Mark ticket as scanned
    $update = $conn->prepare("UPDATE tickets_tb SET scanned = 1, dateofattendance = NOW() WHERE id = ?");
    $update->execute([$ticket['id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Ticket successfully scanned ✅'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
$conn = null;
?>