<?php
require '../vendor/autoload.php';
use HeroQR\Core\QRCodeGenerator;

# Create a QRCodeGenerator instance
$qrCodeManager = new QRCodeGenerator();

$qrCode = $qrCodeManager
    # Set the data to be encoded in the QR code
    ->setData('https://test.org') 
    # Generate the QR code in PNG format (default)
    ->generate();

# Save the generated QR code to a file named 'qrcode.png'
$qrCode->saveTo('qrcode'); 