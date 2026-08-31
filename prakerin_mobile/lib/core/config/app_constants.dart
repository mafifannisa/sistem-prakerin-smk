class AppConstants {
  // Ganti IP ini dengan IP server lokal atau domain API Anda
  static const String baseUrl = 'http://10.0.2.2:8000/api/v1'; // Default emulator Android / sesuaikan
  
  // Storage Keys
  static const String keyToken = 'auth_token';
  static const String keyUser = 'user_data';
  static const String keyPlacement = 'placement_data';
  static const String keyDeviceId = 'device_uuid';
  
  // Geofence & Location Thresholds
  static const double defaultRadiusToleranceMeters = 300.0;
  static const double maxGpsAccuracyMeters = 50.0;
}
