import 'dart:io';
import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../../../../core/services/storage_service.dart';
import '../models/user_model.dart';

class AuthRemoteDataSource {
  final ApiClient _client = ApiClient();

  Future<Map<String, dynamic>> login({
    required String nisn,
    required String password,
    required String deviceId,
    String? deviceModel,
    String? fcmToken,
  }) async {
    try {
      final response = await _client.dio.post(
        '/auth/login',
        data: {
          'nisn': nisn,
          'password': password,
          'device_id': deviceId,
          'device_model': deviceModel,
          'fcm_token': fcmToken,
        },
      );

      if (response.data['success'] == true) {
        final data = response.data['data'];
        final token = data['token'];
        await StorageService().saveToken(token);

        return {
          'user': UserModel.fromJson(data['user']),
          'placement': data['penempatan'] != null ? PlacementModel.fromJson(data['penempatan']) : null,
        };
      } else {
        throw Exception(response.data['message'] ?? 'Login gagal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Terjadi kesalahan jaringan (${e.message})';
      throw Exception(msg);
    }
  }

  Future<void> faceEnroll({
    required File photo,
    String? faceEmbedding,
  }) async {
    try {
      final formData = FormData.fromMap({
        'foto_master_1': await MultipartFile.fromFile(photo.path, filename: 'master_face.jpg'),
        if (faceEmbedding != null) 'face_embedding': faceEmbedding,
      });

      final response = await _client.dio.post(
        '/auth/face-enroll',
        data: formData,
      );

      if (response.data['success'] != true) {
        throw Exception(response.data['message'] ?? 'Perekaman wajah gagal');
      }
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? 'Terjadi kesalahan upload (${e.message})';
      throw Exception(msg);
    }
  }

  Future<void> logout() async {
    try {
      await _client.dio.post('/auth/logout');
    } catch (_) {}
    await StorageService().clearAuth();
  }
}
