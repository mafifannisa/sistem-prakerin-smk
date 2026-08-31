import 'dart:io';
import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../models/journal_model.dart';

class JournalRemoteDataSource {
  final ApiClient _client = ApiClient();

  Future<List<JournalModel>> getJournals({int? mingguKe}) async {
    try {
      final response = await _client.dio.get(
        '/jurnal',
        queryParameters: {
          if (mingguKe != null) 'minggu_ke': mingguKe,
        },
      );

      if (response.data['success'] == true) {
        final list = response.data['data'] as List<dynamic>;
        return list.map((j) => JournalModel.fromJson(j)).toList();
      } else {
        throw Exception(response.data['message'] ?? 'Gagal memuat jurnal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Terjadi kesalahan jaringan';
      throw Exception(msg);
    }
  }

  Future<void> createJournal({
    required String tanggal,
    required int mingguKe,
    required String kegiatan,
    required double durasiJam,
    required List<File> photos,
  }) async {
    try {
      final formDataMap = <String, dynamic>{
        'tanggal': tanggal,
        'minggu_ke': mingguKe,
        'kegiatan': kegiatan,
        'durasi_jam': durasiJam,
      };

      for (int i = 0; i < photos.length; i++) {
        formDataMap['fotos[$i]'] = await MultipartFile.fromFile(
          photos[i].path,
          filename: 'journal_photo_$i.jpg',
        );
      }

      final formData = FormData.fromMap(formDataMap);
      final response = await _client.dio.post('/jurnal', data: formData);

      if (response.data['success'] != true) {
        throw Exception(response.data['message'] ?? 'Gagal menyimpan jurnal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Terjadi kesalahan saat upload jurnal';
      throw Exception(msg);
    }
  }
}
