class GeofenceCheckResult {
  final bool isWithinRadius;
  final double nearestDistanceMeters;
  final String zoneName;
  final int allowedRadius;
  final double? gpsAccuracyMeters;
  final bool canCheckIn;
  final bool canCheckOut;
  final String message;

  GeofenceCheckResult({
    required this.isWithinRadius,
    required this.nearestDistanceMeters,
    required this.zoneName,
    required this.allowedRadius,
    this.gpsAccuracyMeters,
    required this.canCheckIn,
    required this.canCheckOut,
    required this.message,
  });

  factory GeofenceCheckResult.fromJson(Map<String, dynamic> json) {
    return GeofenceCheckResult(
      isWithinRadius: json['is_within_radius'] == true,
      nearestDistanceMeters: (json['nearest_distance_meters'] as num?)?.toDouble() ?? 0.0,
      zoneName: json['zone_name'] ?? 'Zona Industri',
      allowedRadius: json['allowed_radius'] ?? 300,
      gpsAccuracyMeters: (json['gps_accuracy_meters'] as num?)?.toDouble(),
      canCheckIn: json['can_check_in'] == true,
      canCheckOut: json['can_check_out'] == true,
      message: json['message'] ?? '',
    );
  }
}

class TodayAttendanceStatus {
  final String tanggal;
  final String serverTime;
  final bool sudahAbsen;
  final String? status;
  final String? jamMasuk;
  final String? jamPulang;
  final String? fotoMasukUrl;
  final String? fotoPulangUrl;

  TodayAttendanceStatus({
    required this.tanggal,
    required this.serverTime,
    required this.sudahAbsen,
    this.status,
    this.jamMasuk,
    this.jamPulang,
    this.fotoMasukUrl,
    this.fotoPulangUrl,
  });

  factory TodayAttendanceStatus.fromJson(Map<String, dynamic> json) {
    final absensi = json['absensi'] as Map<String, dynamic>?;

    return TodayAttendanceStatus(
      tanggal: json['tanggal'] ?? '',
      serverTime: json['server_time'] ?? '',
      sudahAbsen: json['sudah_absen'] == true,
      status: absensi?['status'],
      jamMasuk: absensi?['jam_masuk'],
      jamPulang: absensi?['jam_pulang'],
      fotoMasukUrl: absensi?['foto_masuk_url'],
      fotoPulangUrl: absensi?['foto_pulang_url'],
    );
  }
}
