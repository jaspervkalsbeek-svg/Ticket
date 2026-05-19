<?php
require_once '../includes/db.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: festivals.php');
    exit;
}

$event_id     = (int)$_POST['event_id'];
$fname        = trim($_POST['Fname']);
$sname        = trim($_POST['Sname']);
$email        = trim($_POST['Email']);
$aanhef       = trim($_POST['Aanhef']);
$aantal       = (int)$_POST['aantal'];
$tickets      = $_POST['tickets'] ?? [];

if (empty($tickets) || $aantal < 1) {
    header('Location: order.php?event_id=' . $event_id);
    exit;
}

$total_price = 0;
foreach ($tickets as $t) {
    $total_price += (float)$t['price'];
}

try {
    $stmt = $conn->prepare('
        INSERT INTO orders (Fname, Lname, email, Aanhef, event_id, total_price, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');
    $stmt->execute([$fname, $sname, $email, $aanhef, $event_id, $total_price]);
    $order_id = $conn->lastInsertId();
} catch (PDOException $e) {
    die('Order opslaan mislukt: ' . $e->getMessage());
}

$savedTickets = [];

foreach ($tickets as $t) {
    $ticket_id   = strtoupper(bin2hex(random_bytes(6))); 
    $ticket_name = trim($t['name']);
    $type_id     = (int)$t['type_id'];
    $price       = (float)$t['price'];
    $coupon      = trim($t['coupon'] ?? '');

    try {
        $stmt = $conn->prepare('
            INSERT INTO tickets_tb (email, ticket_id, Fname, Lname, date, scanned, order_id, ticket_type_id)
            VALUES (?, ?, ?, ?, NOW(), 0, ?, ?)
        ');
        $stmt->execute([$email, $ticket_id, $fname, $sname, $order_id, $type_id]);
    } catch (PDOException $e) {
        die('Ticket opslaan mislukt: ' . $e->getMessage());
    }

    $typeStmt = $conn->prepare('SELECT name FROM ticket_type_tb WHERE id = ? LIMIT 1');
    $typeStmt->execute([$type_id]);
    $typeName = $typeStmt->fetchColumn();

    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($ticket_id);

    $savedTickets[] = [
        'ticket_id'   => $ticket_id,
        'name'        => $ticket_name,
        'type'        => $typeName,
        'price'       => $price,
        'qr_url'      => $qrUrl,
    ];
}


$evStmt = $conn->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
$evStmt->execute([$event_id]);
$event = $evStmt->fetch(PDO::FETCH_ASSOC);

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor(30, 30, 30);
        $this->Cell(0, 12, 'Ticket Bevestiging', 0, 1, 'C');
        $this->SetDrawColor(255, 214, 0);
        $this->SetLineWidth(0.8);
        $this->Line(10, 22, 200, 22);
        $this->Ln(6);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

// Order info block
$pdf->SetFont('Helvetica', 'B', 11);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 7, 'Bestelling #' . $order_id, 0, 1);
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(0, 6, 'Naam: ' . $fname . ' ' . $sname, 0, 1);
$pdf->Cell(0, 6, 'E-mail: ' . $email, 0, 1);
$pdf->Cell(0, 6, 'Evenement: ' . $event['name'], 0, 1);
$pdf->Cell(0, 6, 'Datum: ' . date('d M Y', strtotime($event['start_date'])), 0, 1);
$pdf->Cell(0, 6, 'Locatie: ' . $event['location'], 0, 1);
$pdf->Cell(0, 6, 'Totaal betaald: EUR ' . number_format($total_price, 2, ',', '.'), 0, 1);
$pdf->Ln(6);


foreach ($savedTickets as $i => $t) {
    if ($pdf->GetY() > 220) $pdf->AddPage();

   
    $pdf->SetFillColor(255, 214, 0);
    $pdf->SetTextColor(20, 20, 20);
    $pdf->SetFont('Helvetica', 'B', 11);
    $pdf->Cell(0, 8, 'Ticket ' . ($i + 1) . ' - ' . $t['type'], 0, 1, 'L', true);
    $pdf->Ln(2);

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->SetTextColor(50, 50, 50);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell(120, 6,
        "Naam: " . $t['name'] . "\n" .
        "Type: " . $t['type'] . "\n" .
        "Prijs: EUR " . number_format($t['price'], 2, ',', '.') . "\n" .
        "Ticket ID: " . $t['ticket_id'],
        0, 'L'
    );

   
    $qrTmp = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    $qrData = @file_get_contents($t['qr_url']);
    if ($qrData) {
        file_put_contents($qrTmp, $qrData);
        $pdf->Image($qrTmp, 155, $y, 40, 40);
        unlink($qrTmp);
    }

    $pdf->SetY($y + 44);
    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(6);
}

$pdfOutput = $pdf->Output('S');

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jasper.v.kalsbeek@gmail.com'; // ← change this
    $mail->Password   = 'xdsq mhma llds iioq';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('jasper.v.kalsbeek@gmail.com', 'Spik & Span Festival'); // ← change this
    $mail->addAddress($email, $fname . ' ' . $sname);

    $mail->isHTML(true);
    $mail->Subject = 'Jouw tickets voor ' . $event['name'];
    $mail->Body    = '
        <div style="font-family: sans-serif; max-width: 600px; margin: auto;">
            <h2 style="color: #FFD600;">Bedankt voor je bestelling, ' . htmlspecialchars($fname) . '!</h2>
            <p>Je tickets voor <strong>' . htmlspecialchars($event['name']) . '</strong> zijn bijgevoegd als PDF.</p>
            <p><strong>Bestelnummer:</strong> #' . $order_id . '</p>
            <p><strong>Totaal:</strong> €' . number_format($total_price, 2, ',', '.') . '</p>
            <p style="margin-top: 24px; color: #888;">Tot ziens op het festival!</p>
        </div>
    ';
    $mail->AltBody = 'Bedankt voor je bestelling! Je tickets zijn bijgevoegd als PDF.';

    $mail->addStringAttachment($pdfOutput, 'tickets_' . $order_id . '.pdf');

    $mail->send();

} catch (Exception $e) {
    error_log('Mailer error: ' . $mail->ErrorInfo);
}

header('Location: confirmation.php?order_id=' . $order_id);
exit;