import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import '../../../../core/config/app_theme.dart';
import '../cubit/auth_cubit.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class FaceEnrollmentPage extends StatefulWidget {
  const FaceEnrollmentPage({super.key});

  @override
  State<FaceEnrollmentPage> createState() => _FaceEnrollmentPageState();
}

class _FaceEnrollmentPageState extends State<FaceEnrollmentPage> {
  File? _capturedFace;
  bool _isProcessing = false;

  Future<void> _takeSelfie() async {
    final picker = ImagePicker();
    final image = await picker.pickImage(
      source: ImageSource.camera,
      preferredCameraDevice: CameraDevice.front,
      maxWidth: 1000,
      imageQuality: 85,
    );

    if (image != null) {
      setState(() {
        _capturedFace = File(image.path);
      });
    }
  }

  void _submitEnrollment() {
    if (_capturedFace == null) return;
    setState(() => _isProcessing = true);
    context.read<AuthCubit>().enrollFace(_capturedFace!).then((_) {
      if (mounted) setState(() => _isProcessing = false);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('Perekaman Wajah Master'),
        automaticallyImplyLeading: false,
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            children: [
              const SizedBox(height: 10),
              const Text(
                'Daftarkan Wajah Anda',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.primaryNavy,
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                'Foto ini akan digunakan sebagai data acuan verifikasi kehadiran setiap presensi masuk & pulang.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: Colors.grey),
              ),
              const Spacer(),

              // Circular Face Guide
              Center(
                child: Container(
                  width: 220,
                  height: 220,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(
                      color: _capturedFace != null ? AppTheme.emerald : Colors.grey.shade300,
                      width: 4,
                    ),
                  ),
                  child: ClipOval(
                    child: _capturedFace != null
                        ? Image.file(_capturedFace!, fit: BoxFit.cover)
                        : const Center(
                            child: Icon(
                              Icons.face_retouching_natural,
                              size: 80,
                              color: Colors.grey,
                            ),
                          ),
                  ),
                ),
              ),
              const Spacer(),

              // Instructions
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.blue.shade50,
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.info_outline, color: Colors.blue),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Pastikan pencahayaan cukup, tidak memakai masker/kacamata hitam, dan wajah menghadap lurus ke kamera.',
                        style: TextStyle(fontSize: 12, color: Colors.blue),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              if (_capturedFace == null)
                SizedBox(
                  width: double.infinity,
                  height: 52,
                  child: ElevatedButton.icon(
                    onPressed: _takeSelfie,
                    icon: const Icon(Icons.camera_alt),
                    label: const Text('AMBIL FOTO MASTER'),
                  ),
                )
              else
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: _takeSelfie,
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: const Text('FOTO ULANG'),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: _isProcessing ? null : _submitEnrollment,
                        child: _isProcessing
                            ? const SizedBox(
                                width: 20,
                                height: 20,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Text('SIMPAN WAJAH'),
                      ),
                    ),
                  ],
                ),
            ],
          ),
        ),
      ),
    );
  }
}
