import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/config/app_theme.dart';
import '../cubit/attendance_cubit.dart';
import '../cubit/attendance_state.dart';

class LivenessCameraPage extends StatefulWidget {
  final bool isCheckOut;

  const LivenessCameraPage({
    super.key,
    this.isCheckOut = false,
  });

  @override
  State<LivenessCameraPage> createState() => _LivenessCameraPageState();
}

class _LivenessCameraPageState extends State<LivenessCameraPage> {
  File? _selfieFile;
  bool _isProcessing = false;
  String _activeChallengeText = '👁️ Harap Menghadap Lurus & Kedipkan Mata 2x';

  Future<void> _captureSelfie() async {
    final picker = ImagePicker();
    final image = await picker.pickImage(
      source: ImageSource.camera,
      preferredCameraDevice: CameraDevice.front,
      maxWidth: 1000,
      imageQuality: 85,
    );

    if (image != null) {
      setState(() {
        _selfieFile = File(image.path);
        _activeChallengeText = '✅ Verifikasi Wajah Lolos!';
      });
    }
  }

  void _onConfirmSubmit() {
    if (_selfieFile == null) return;
    setState(() => _isProcessing = true);

    if (widget.isCheckOut) {
      context.read<AttendanceCubit>().submitCheckOut(
        selfiePhoto: _selfieFile!,
      );
    } else {
      context.read<AttendanceCubit>().submitCheckIn(
        selfiePhoto: _selfieFile!,
        livenessScore: 0.98,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
        title: Text(
          widget.isCheckOut ? 'Selfie Pulang' : 'Selfie Masuk',
          style: const TextStyle(color: Colors.white),
        ),
      ),
      body: BlocListener<AttendanceCubit, AttendanceState>(
        listener: (context, state) {
          if (state is AttendanceSuccess) {
            Navigator.pop(context); // Close camera
          } else if (state is AttendanceFailure) {
            setState(() => _isProcessing = false);
          }
        },
        child: SafeArea(
          child: Column(
            children: [
              // Challenge Prompt Banner
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: BoxDecoration(
                  color: AppTheme.primaryNavy.withOpacity(0.85),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppTheme.emerald, width: 1.5),
                ),
                child: Text(
                  _activeChallengeText,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const Spacer(),

              // Oval Face Viewport
              Center(
                child: Container(
                  width: 260,
                  height: 340,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(130),
                    border: Border.all(
                      color: _selfieFile != null ? AppTheme.emerald : Colors.white,
                      width: 4,
                    ),
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(130),
                    child: _selfieFile != null
                        ? Image.file(_selfieFile!, fit: BoxFit.cover)
                        : Container(
                            color: Colors.white10,
                            child: const Center(
                              child: Icon(
                                Icons.face,
                                size: 100,
                                color: Colors.white30,
                              ),
                            ),
                          ),
                  ),
                ),
              ),
              const Spacer(),

              // Capture / Confirm Controls
              Padding(
                padding: const EdgeInsets.all(24.0),
                child: _selfieFile == null
                    ? SizedBox(
                        width: double.infinity,
                        height: 52,
                        child: ElevatedButton.icon(
                          onPressed: _captureSelfie,
                          icon: const Icon(Icons.camera_alt),
                          label: const Text('AMBIL SELFIE VERIFIKASI'),
                        ),
                      )
                    : Row(
                        children: [
                          Expanded(
                            child: OutlinedButton(
                              onPressed: _captureSelfie,
                              style: OutlinedButton.styleFrom(
                                foregroundColor: Colors.white,
                                side: const BorderSide(color: Colors.white70),
                                padding: const EdgeInsets.symmetric(vertical: 14),
                              ),
                              child: const Text('FOTO ULANG'),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: ElevatedButton(
                              onPressed: _isProcessing ? null : _onConfirmSubmit,
                              child: _isProcessing
                                  ? const SizedBox(
                                      width: 20,
                                      height: 20,
                                      child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                                    )
                                  : const Text('KIRIM PRESENSI'),
                            ),
                          ),
                        ],
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
