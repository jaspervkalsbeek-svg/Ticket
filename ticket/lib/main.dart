import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Ticket Scanner',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(primarySwatch: Colors.blue, useMaterial3: true),
      home: const ScannerScreen(),
    );
  }
}

class ScannerScreen extends StatefulWidget {
  const ScannerScreen({super.key});

  @override
  State<ScannerScreen> createState() => _ScannerScreenState();
}

class _ScannerScreenState extends State<ScannerScreen> {
  static const String serverUrl = 'http://10.121.94.165/ticket/ticket/ScanTicket.php';
  String? result;
  bool isProcessing = false;

  Future<void> sendToServer(String qrData) async {
    setState(() => isProcessing = true);



    try {
      final response = await http.post(
        Uri.parse(serverUrl),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'ticket_id': qrData}),
      ).timeout (const Duration(seconds: 5));

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final bool success = data['success'] ?? false;
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(data['message'] ?? 'Done'),
            backgroundColor: success ? Colors.green : Colors.orange,
          ),
        );
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Server error'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      String errorMsg = 'Connection error';
      if (e.toString().contains('Timeout')) {
        errorMsg = 'Request timed out - Server not responding';
      } else if (e.toString().contains('Connection refused')) {
        errorMsg = 'Cannot connect to server';
      }
      
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(errorMsg), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => isProcessing = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ticket QR Scanner')),
      body: Column(
        children: [
          Expanded(
            child: MobileScanner(
              onDetect: (capture) async {
                final barcode = capture.barcodes.firstOrNull;
                if (barcode?.rawValue != null && !isProcessing) {
                  final qrCode = barcode!.rawValue!;
                  if (qrCode == result) return;
                  setState(() => result = qrCode);

                  await sendToServer(qrCode);
                }
              },
            ),
          ),
          Container(
            padding: const EdgeInsets.all(20),
            color: Colors.black87,
            width: double.infinity,
            child: Column(
              children: [
                Text(
                  result ?? "Scan a ticket QR code",
                  style: const TextStyle(color: Colors.white, fontSize: 16),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 10),
                if (isProcessing)
                  const CircularProgressIndicator(color: Colors.white),
              ],
            ),
          ),
        ],
      ),
    );
  }
}