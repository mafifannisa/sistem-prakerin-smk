import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/config/app_theme.dart';
import '../cubit/attendance_cubit.dart';
import '../cubit/attendance_state.dart';

class EmergencyKoreksiPage extends StatefulWidget {
  final bool isCheckOut;

  const EmergencyKoreksiPage({
    super.key,
    this.isCheckOut = false,
  });

  @override
  State<EmergencyKoreksiPage> createState() => _EmergencyKoreksiPageState();
}

class _EmergencyKoreksiPageState extends State<EmergencyKoreksiPage> {
  final _alasanController = TextEditingController();
  String _jenisKoreksi = 'masuk';
  String _jamDiajukan = DateFormat('HH:mm:ss').format(DateTime.now());
  File? _buktiLampiran;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    if (widget.isCheckOut) {
      _jenisKoreksi = 'pulang';
    }
  }

  @override
  void dispose() {
    _alasanController.dispose();
    super.dispose();
  }

  Future<void> _pickBukti() async {
    final picker = ImagePicker();
    final image = await picker.pickImage(
      source: ImageSource.camera,
      maxWidth: 1200,
      imageQuality: 80,
    );

    if (image != null) {
      setState(() {
        _buktiLampiran = File(image.path);
      });
    }
  }

  void _submitKoreksi() {
    final alasan = _alasanController.text.trim();
    if (alasan.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Alasan kendala wajib diisi!')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    final tanggal = DateFormat('yyyy-MM-dd').format(DateTime.now());

    context.read<AttendanceCubit>().submitEmergencyKoreksi(
      tanggal: tanggal,
      jenisKoreksi: _jenisKoreksi,
      jamDiajukan: _jamDiajukan,
      alasan: alasan,
      buktiLampiran: _buktiLampiran,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Presensi Darurat (Koreksi)'),
      ),
      body: BlocListener<AttendanceCubit, AttendanceState>(
        listener: (context, state) {
          if (state is AttendanceSuccess) {
            Navigator.pop(context);
          } else if (state is AttendanceFailure) {
            setState(() => _isSubmitting = false);
          }
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Notice Alert
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.amber.shade50,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: Colors.amber.shade300),
                ),
                child: Row(
                  children: [
                    Icon(Icons.warning_amber_rounded, color: Colors.amber.shade800),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Fitur ini untuk kendala darurat (sinyal hilang di basement, GPS drift, atau kamera error). Maksimal 3 kali per bulan dan wajib disetujui Guru Pembimbing.',
                        style: TextStyle(fontSize: 12, color: Colors.amber.shade900),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              // Jenis Koreksi
              const Text(
                'Jenis Presensi',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy),
              ),
              const SizedBox(height: 8),
              DropdownButtonFormField<String>(
                initialValue: _jenisKoreksi,
                decoration: const InputDecoration(),
                items: const [
                  DropdownMenuItem(value: 'masuk', child: Text('Presensi Masuk')),
                  DropdownMenuItem(value: 'pulang', child: Text('Presensi Pulang')),
                  DropdownMenuItem(value: 'penuh', child: Text('Masuk & Pulang Sekaligus')),
                ],
                onChanged: (val) {
                  if (val != null) setState(() => _jenisKoreksi = val);
                },
              ),
              const SizedBox(height: 18),

              // Jam Presensi Sebenarnya
              const Text(
                'Jam Presensi Sebenarnya (WIB)',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy),
              ),
              const SizedBox(height: 8),
              TextFormField(
                initialValue: _jamDiajukan,
                decoration: const InputDecoration(
                  prefixIcon: Icon(Icons.access_time),
                ),
                onChanged: (val) => _jamDiajukan = val,
              ),
              const SizedBox(height: 18),

              // Alasan Kendala
              const Text(
                'Alasan / Deskripsi Kendala *',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy),
              ),
              const SizedBox(height: 8),
              TextFormField(
                controller: _alasanController,
                maxLines: 3,
                decoration: const InputDecoration(
                  hintText: 'Contoh: Berada di lantai dasar gedung logam sehingga GPS tidak akurat, telah melapor ke instruktur lapangan Pak Budi.',
                ),
              ),
              const SizedBox(height: 18),

              // Lampiran Bukti Foto
              const Text(
                'Foto Bukti (Opsional / Lokasi/Surat)',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy),
              ),
              const SizedBox(height: 8),
              InkWell(
                onTap: _pickBukti,
                borderRadius: BorderRadius.circular(14),
                child: Container(
                  height: 140,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(14),
                    border: Border.all(color: Colors.grey.shade300, style: BorderStyle.solid),
                  ),
                  child: _buktiLampiran != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(14),
                          child: Image.file(_buktiLampiran!, fit: BoxFit.cover),
                        )
                      : const Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.add_a_photo_outlined, color: Colors.grey, size: 36),
                              SizedBox(height: 8),
                              Text('Sentuh untuk mengambil foto bukti', style: TextStyle(fontSize: 12, color: Colors.grey)),
                            ],
                          ),
                        ),
                ),
              ),
              const SizedBox(height: 32),

              // Submit Button
              SizedBox(
                width: double.infinity,
                height: 52,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submitKoreksi,
                  child: _isSubmitting
                      ? const SizedBox(
                          width: 24,
                          height: 24,
                          child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                        )
                      : const Text('KIRIM TIKET KOREKSI'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
