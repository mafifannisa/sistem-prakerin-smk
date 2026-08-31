import 'dart:io';
import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../models/attendance_model.dart';

class AttendanceRemoteDataSource {
  final ApiClient _client = ApiClient();

  Future<GeofenceCheckResult> checkLocation({
    required double latitude,
    required double longitude,
    double? accuracy,
  }) async {
    try {
      final response = await _client.dio.post(
        '/absensi/check-location',
        data: {
          'latitude': latitude,
          'longitude': longitude,
          'accuracy': accuracy,
        },
      );

      if (response.data['success'] == true) {
        return GeofenceCheckResult.fromJson(response.data['data']);
      } else {
        throw Exception(response.data['message'] ?? 'Gagal memeriksa lokasi');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Gagal menghubungi server';
      throw Exception(msg);
    }
  }

  Future<void> submitCheckIn({
    required double latitude,
    required double longitude,
    double? accuracy,
    required File selfiePhoto,
    bool isMockLocation = false,
    double? livenessScore,
  }) async {
    try {
      final formData = FormData.fromMap({
        'latitude': latitude,
        'longitude': longitude,
        if (accuracy != null) 'accuracy': accuracy,
        'is_mock_location': isMockLocation ? 1 : 0,
        if (livenessScore != null) 'liveness_score': livenessScore,
        'foto_selfie': await MultipartFile.fromFile(
          selfiePhoto.path,
          filename: 'checkin_selfie.jpg',
        ),
      });

      final response = await _client.dio.post(
        '/absensi/check-in',
        data: formData,
      );

      if (response.data['success'] != true) {
        throw Exception(response.data['message'] ?? 'Presensi masuk gagal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Gagal submit presensi';
      throw Exception(msg);
    }
  }

  Future<void> submitCheckOut({
    required double latitude,
    required double longitude,
    double? accuracy,
    required File selfiePhoto,
    bool isMockLocation = false,
  }) async {
    try {
      final formData = FormData.fromMap({
        'latitude': latitude,
        'longitude': longitude,
        if (accuracy != null) 'accuracy': accuracy,
        'is_mock_location': isMockLocation ? 1 : 0,
        'foto_selfie': await MultipartFile.fromFile(
          selfiePhoto.path,
          filename: 'checkout_selfie.jpg',
        ),
      });

      final response = await _client.dio.post(
        '/absensi/check-out',
        data: formData,
      );

      if (response.data['success'] != true) {
        throw Exception(response.data['message'] ?? 'Presensi pulang gagal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Gagal submit presensi pulang';
      throw Exception(msg);
    }
  }

  Future<TodayAttendanceStatus> getTodayStatus() async {
    try {
      final response = await _client.dio.get('/absensi/today');
      if (response.data['success'] == true) {
        return TodayAttendanceStatus.fromJson(response.data['data']);
      } else {
        throw Exception(response.data['message'] ?? 'Gagal memuat status hari ini');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Gagal memuat status';
      throw Exception(msg);
    }
  }

  Future<void> submitEmergencyKoreksi({
    required String tanggal,
    required String jenisKoreksi,
    required String jamDiajukan,
    required String alasan,
    File? buktiLampiran,
  }) async {
    try {
      final formData = FormData.fromMap({
        'tanggal': tanggal,
        'jenis_koreksi': jenisKoreksi,
        'jam_diajukan': jamDiajukan,
        'alasan': alasan,
        if (buktiLampiran != null)
          'bukti_lampiran': await MultipartFile.fromFile(
            buktiLampiran.path,
            filename: 'bukti_koreksi.jpg',
          ),
      });

      final response = await _client.dio.post(
        '/absensi/koreksi',
        data: formData,
      );

      if (response.data['success'] != true) {
        throw Exception(response.data['message'] ?? 'Pengajuan koreksi gagal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Gagal mengajukan koreksi';
      throw Exception(msg);
    }
  }
}
