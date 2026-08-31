import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/config/app_theme.dart';
import '../cubit/journal_cubit.dart';
import '../cubit/journal_state.dart';
import 'journal_editor_page.dart';

class JournalListPage extends StatefulWidget {
  const JournalListPage({super.key});

  @override
  State<JournalListPage> createState() => _JournalListPageState();
}

class _JournalListPageState extends State<JournalListPage> {
  @override
  void initState() {
    super.initState();
    context.read<JournalCubit>().fetchJournals();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Jurnal Harian'),
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppTheme.emerald,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add),
        label: const Text('TULIS JURNAL'),
        onPressed: () async {
          final res = await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const JournalEditorPage()),
          );
          if (res == true && mounted) {
            context.read<JournalCubit>().fetchJournals();
          }
        },
      ),
      body: BlocConsumer<JournalCubit, JournalState>(
        listener: (context, state) {
          if (state is JournalError) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.error), backgroundColor: AppTheme.crimson),
            );
          }
        },
        builder: (context, state) {
          if (state is JournalLoading) {
            return const Center(child: CircularProgressIndicator(color: AppTheme.emerald));
          }

          if (state is JournalLoaded) {
            final journals = state.journals;
            if (journals.isEmpty) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.edit_note_rounded, size: 64, color: Colors.grey.shade300),
                    const SizedBox(height: 12),
                    const Text('Belum ada jurnal yang ditulis.', style: TextStyle(color: Colors.grey, fontWeight: FontWeight.w600)),
                    const SizedBox(height: 8),
                    const Text('Tekan tombol di bawah untuk menulis kegiatan hari ini.', style: TextStyle(fontSize: 12, color: Colors.grey)),
                  ],
                ),
              );
            }

            return RefreshIndicator(
              color: AppTheme.emerald,
              onRefresh: () => context.read<JournalCubit>().fetchJournals(),
              child: ListView.separated(
                padding: const EdgeInsets.all(16),
                itemCount: journals.length,
                separatorBuilder: (_, __) => const SizedBox(height: 12),
                itemBuilder: (context, index) {
                  final j = journals[index];

                  Color statusColor = Colors.amber;
                  String statusLabel = 'Menunggu';
                  if (j.status == 'approved' || j.status == 'disetujui') {
                    statusColor = Colors.green;
                    statusLabel = 'Disetujui';
                  } else if (j.status == 'rejected' || j.status == 'ditolak') {
                    statusColor = Colors.red;
                    statusLabel = 'Ditolak / Revisi';
                  }

                  return Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.03),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              j.tanggal,
                              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: AppTheme.primaryNavy),
                            ),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(
                                color: statusColor.withOpacity(0.12),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(
                                statusLabel,
                                style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: statusColor),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Minggu Ke-${j.mingguKe} • ${j.durasiJam} Jam Kerja',
                          style: const TextStyle(fontSize: 12, color: Colors.grey),
                        ),
                        const SizedBox(height: 10),
                        Text(
                          j.kegiatan,
                          style: const TextStyle(fontSize: 13, color: AppTheme.secondaryNavy, height: 1.4),
                        ),
                        if (j.catatanPembimbing != null && j.catatanPembimbing!.isNotEmpty) ...[
                          const SizedBox(height: 10),
                          Container(
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(
                              color: Colors.amber.shade50,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Text(
                              'Catatan Guru: ${j.catatanPembimbing}',
                              style: TextStyle(fontSize: 12, color: Colors.amber.shade900),
                            ),
                          ),
                        ],
                        if (j.fotos.isNotEmpty) ...[
                          const SizedBox(height: 12),
                          SizedBox(
                            height: 60,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              itemCount: j.fotos.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 8),
                              itemBuilder: (context, pIdx) {
                                return ClipRRect(
                                  borderRadius: BorderRadius.circular(8),
                                  child: Image.network(
                                    j.fotos[pIdx].url,
                                    width: 60,
                                    height: 60,
                                    fit: BoxFit.cover,
                                    errorBuilder: (_, __, ___) => Container(
                                      width: 60,
                                      height: 60,
                                      color: Colors.grey.shade200,
                                      child: const Icon(Icons.broken_image, color: Colors.grey),
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ],
                    ),
                  );
                },
              ),
            );
          }

          return const SizedBox.shrink();
        },
      ),
    );
  }
}
