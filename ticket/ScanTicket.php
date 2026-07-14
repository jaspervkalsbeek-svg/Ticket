<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
 
require_once '../includes/db.php'; 
 
function respond(bool $success, string $message, int $httpCode = 200): void {
    http_response_code($httpCode);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}
 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$body = json_decode(file_get_contents('php://input'), true);
 
if (!isset($body['ticket_id'])) {
    respond(false, 'Missing QR-code.', 400);
}
 
$qrCode = trim($body['ticket_id']);
 
try {
    $stmt = $conn->prepare('SELECT id, scanned FROM tickets_tb WHERE ticket_id = ? LIMIT 1');
    $stmt->execute([$qrCode]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    respond(false, 'Database query failed.', 500);
}
 
if (!$ticket) {
    respond(false, 'Ticket not found.');
}
 
if ((bool)$ticket['scanned'] === true) {
    respond(false, 'Ticket has already been scanned.');
}
 
try {
    $update = $conn->prepare('UPDATE tickets_tb SET scanned = 1 WHERE id = ?');
    $update->execute([$ticket['id']]);
} catch (PDOException $e) {
    respond(false, 'Failed to update ticket.', 500);
}
 
respond(true, 'Ticket scanned successfully.');