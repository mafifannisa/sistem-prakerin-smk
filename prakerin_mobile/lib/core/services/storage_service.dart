import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:uuid/uuid.dart';
import '../config/app_constants.dart';

class StorageService {
  static final StorageService _instance = StorageService._internal();
  factory StorageService() => _instance;
  StorageService._internal();

  final FlutterSecureStorage _secureStorage = const FlutterSecureStorage();
  late SharedPreferences _prefs;

  Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Token Management
  Future<void> saveToken(String token) async {
    await _secureStorage.write(key: AppConstants.keyToken, value: token);
  }

  Future<String?> getToken() async {
    return await _secureStorage.read(key: AppConstants.keyToken);
  }

  Future<void> clearAuth() async {
    await _secureStorage.delete(key: AppConstants.keyToken);
    await _prefs.remove(AppConstants.keyUser);
    await _prefs.remove(AppConstants.keyPlacement);
  }

  // Device UUID
  Future<String> getOrCreateDeviceId() async {
    String? deviceId = _prefs.getString(AppConstants.keyDeviceId);
    if (deviceId == null || deviceId.isEmpty) {
      deviceId = const Uuid().v4();
      await _prefs.setString(AppConstants.keyDeviceId, deviceId);
    }
    return deviceId;
  }

  // User Profile Caching
  Future<void> saveUserRaw(String jsonString) async {
    await _prefs.setString(AppConstants.keyUser, jsonString);
  }

  String? getUserRaw() {
    return _prefs.getString(AppConstants.keyUser);
  }
}
