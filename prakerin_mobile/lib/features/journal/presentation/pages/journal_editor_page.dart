import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/config/app_theme.dart';
import '../cubit/journal_cubit.dart';
import '../cubit/journal_state.dart';

class JournalEditorPage extends StatefulWidget {
  const JournalEditorPage({super.key});

  @override
  State<JournalEditorPage> createState() => _JournalEditorPageState();
}

class _JournalEditorPageState extends State<JournalEditorPage> {
  final _kegiatanController = TextEditingController();
  final _durasiController = TextEditingController(text: '8');
  final _mingguKeController = TextEditingController(text: '1');
  String _tanggal = DateFormat('yyyy-MM-dd').format(DateTime.now());
  final List<File> _photos = [];
  bool _isSubmitting = false;

  @override
  void dispose() {
    _kegiatanController.dispose();
    _durasiController.dispose();
    _mingguKeController.dispose();
    super.dispose();
  }

  Future<void> _pickPhoto() async {
    if (_photos.length >= 3) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Maksimal 3 foto dokumentasi per kegiatan.')),
      );
      return;
    }

    final picker = ImagePicker();
    final image = await picker.pickImage(
      source: ImageSource.camera,
      maxWidth: 1200,
      imageQuality: 80,
    );

    if (image != null) {
      setState(() {
        _photos.add(File(image.path));
      });
    }
  }

  void _removePhoto(int index) {
    setState(() {
      _photos.removeAt(index);
    });
  }

  void _submitJournal() {
    final kegiatan = _kegiatanController.text.trim();
    final durasi = double.tryParse(_durasiController.text) ?? 8.0;
    final mingguKe = int.tryParse(_mingguKeController.text) ?? 1;

    if (kegiatan.length < 10) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Deskripsi kegiatan minimal 10 karakter!')),
      );
      return;
    }

    if (_photos.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Wajib melampirkan minimal 1 foto dokumentasi pekerjaan!')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    context.read<JournalCubit>().createJournal(
      tanggal: _tanggal,
      mingguKe: mingguKe,
      kegiatan: kegiatan,
      durasiJam: durasi,
      photos: _photos,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Tulis Jurnal Harian'),
      ),
      body: BlocListener<JournalCubit, JournalState>(
        listener: (context, state) {
          if (state is JournalOperationSuccess) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.message), backgroundColor: AppTheme.emerald),
            );
            Navigator.pop(context, true);
          } else if (state is JournalError) {
            setState(() => _isSubmitting = false);
          }
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Tanggal & Minggu Ke
              Row(
                children: [
                  Expanded(
                    flex: 3,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Tanggal Kegiatan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy)),
                        const SizedBox(height: 6),
                        TextFormField(
                          initialValue: _tanggal,
                          decoration: const InputDecoration(
                            prefixIcon: Icon(Icons.calendar_today, size: 18),
                          ),
                          onChanged: (val) => _tanggal = val,
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    flex: 2,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Minggu Ke-', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _mingguKeController,
                          keyboardType: TextInputType.number,
                          decoration: const InputDecoration(
                            hintText: '1',
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 18),

              // Durasi Jam Kerja
              const Text('Durasi Kerja (Jam)', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _durasiController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  hintText: 'Misal: 8',
                  prefixIcon: Icon(Icons.timer_outlined, size: 18),
                ),
              ),
              const SizedBox(height: 18),

              // Deskripsi Kegiatan
              const Text('Deskripsi Kegiatan & Pekerjaan *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _kegiatanController,
                maxLines: 5,
                decoration: const InputDecoration(
                  hintText: 'Jelaskan rincian pekerjaan atau materi yang dipelajari hari ini...',
                ),
              ),
              const SizedBox(height: 18),

              // Foto Dokumentasi (Maks 3)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Dokumentasi Foto (Maks 3)', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.primaryNavy)),
                  Text('${_photos.length}/3 Foto', style: const TextStyle(fontSize: 12, color: Colors.grey)),
                ],
              ),
              const SizedBox(height: 10),

              SizedBox(
                height: 100,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  children: [
                    ...List.generate(_photos.length, (idx) {
                      return Stack(
                        children: [
                          Container(
                            margin: const EdgeInsets.only(right: 12),
                            width: 100,
                            height: 100,
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(12),
                              image: DecorationImage(
                                image: FileImage(_photos[idx]),
                                fit: BoxFit.cover,
                              ),
                            ),
                          ),
                          Positioned(
                            top: 4,
                            right: 16,
                            child: GestureDetector(
                              onTap: () => _removePhoto(idx),
                              child: Container(
                                padding: const EdgeInsets.all(4),
                                decoration: const BoxDecoration(
                                  color: Colors.red,
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(Icons.close, size: 14, color: Colors.white),
                              ),
                            ),
                          ),
                        ],
                      );
                    }),
                    if (_photos.length < 3)
                      InkWell(
                        onTap: _pickPhoto,
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          width: 100,
                          height: 100,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: Colors.grey.shade300, style: BorderStyle.solid),
                          ),
                          child: const Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.add_a_photo_outlined, color: AppTheme.emerald),
                              SizedBox(height: 4),
                              Text('Tambah', style: TextStyle(fontSize: 11, color: AppTheme.emerald, fontWeight: FontWeight.bold)),
                            ],
                          ),
                        ),
                      ),
                  ],
                ),
              ),
              const SizedBox(height: 36),

              // Submit Button
              SizedBox(
                width: double.infinity,
                height: 52,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submitJournal,
                  child: _isSubmitting
                      ? const SizedBox(
                          width: 24,
                          height: 24,
                          child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                        )
                      : const Text('KIRIM JURNAL HARIAN'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
