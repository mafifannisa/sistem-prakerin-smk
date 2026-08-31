import 'dart:io';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../data/datasources/journal_remote_datasource.dart';
import 'journal_state.dart';

class JournalCubit extends Cubit<JournalState> {
  final JournalRemoteDataSource _dataSource = JournalRemoteDataSource();

  JournalCubit() : super(JournalInitial());

  Future<void> fetchJournals({int? mingguKe}) async {
    emit(JournalLoading());
    try {
      final journals = await _dataSource.getJournals(mingguKe: mingguKe);
      emit(JournalLoaded(journals));
    } catch (e) {
      emit(JournalError(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> createJournal({
    required String tanggal,
    required int mingguKe,
    required String kegiatan,
    required double durasiJam,
    required List<File> photos,
  }) async {
    emit(JournalLoading());
    try {
      await _dataSource.createJournal(
        tanggal: tanggal,
        mingguKe: mingguKe,
        kegiatan: kegiatan,
        durasiJam: durasiJam,
        photos: photos,
      );
      emit(const JournalOperationSuccess('Jurnal harian berhasil dikirim!'));
      await fetchJournals();
    } catch (e) {
      emit(JournalError(e.toString().replaceAll('Exception: ', '')));
    }
  }
}
