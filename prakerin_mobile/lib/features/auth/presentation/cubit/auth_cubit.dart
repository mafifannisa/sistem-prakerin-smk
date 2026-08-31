import 'dart:io';
import 'package:device_info_plus/device_info_plus.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/services/storage_service.dart';
import '../../data/datasources/auth_remote_datasource.dart';
import '../../data/models/user_model.dart';
import 'auth_state.dart';

class AuthCubit extends Cubit<AuthState> {
  final AuthRemoteDataSource _dataSource = AuthRemoteDataSource();

  AuthCubit() : super(AuthInitial());

  Future<void> checkAuthStatus() async {
    final token = await StorageService().getToken();
    if (token != null && token.isNotEmpty) {
      // In production, you can call /auth/me to refresh
      emit(Unauthenticated()); // Or load cached user
    } else {
      emit(Unauthenticated());
    }
  }

  Future<void> login(String nisn, String password) async {
    emit(AuthLoading());
    try {
      final storage = StorageService();
      final deviceId = await storage.getOrCreateDeviceId();

      String deviceModel = 'Unknown Device';
      try {
        final deviceInfo = DeviceInfoPlugin();
        if (Platform.isAndroid) {
          final androidInfo = await deviceInfo.androidInfo;
          deviceModel = '${androidInfo.manufacturer} ${androidInfo.model}';
        } else if (Platform.isIOS) {
          final iosInfo = await deviceInfo.iosInfo;
          deviceModel = iosInfo.utsname.machine;
        }
      } catch (_) {}

      final result = await _dataSource.login(
        nisn: nisn,
        password: password,
        deviceId: deviceId,
        deviceModel: deviceModel,
      );

      final user = result['user'] as UserModel;
      final placement = result['placement'] as PlacementModel?;

      emit(Authenticated(user: user, placement: placement));
    } catch (e) {
      emit(AuthError(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> enrollFace(File photo) async {
    emit(AuthLoading());
    try {
      await _dataSource.faceEnroll(photo: photo);
      // Reload user profile
      emit(Unauthenticated());
    } catch (e) {
      emit(AuthError(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> logout() async {
    await _dataSource.logout();
    emit(Unauthenticated());
  }
}
