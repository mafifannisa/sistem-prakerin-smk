class UserModel {
  final int id;
  final String nisn;
  final String nama;
  final String? kelas;
  final String? jurusan;
  final bool isFaceEnrolled;
  final String? fotoMasterWajah;

  UserModel({
    required this.id,
    required this.nisn,
    required this.nama,
    this.kelas,
    this.jurusan,
    required this.isFaceEnrolled,
    this.fotoMasterWajah,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      nisn: json['nisn'] ?? '',
      nama: json['nama'] ?? '',
      kelas: json['kelas'],
      jurusan: json['jurusan'],
      isFaceEnrolled: json['is_face_enrolled'] == true || json['is_face_enrolled'] == 1,
      fotoMasterWajah: json['foto_master_wajah'],
    );
  }
}

class PlacementModel {
  final int id;
  final String status;
  final String? namaIndustri;
  final String? alamatIndustri;
  final double? latitude;
  final double? longitude;
  final int radiusToleransiMeter;
  final String jamMasuk;
  final String jamPulang;
  final String? namaPembimbing;
  final String? noWaPembimbing;

  PlacementModel({
    required this.id,
    required this.status,
    this.namaIndustri,
    this.alamatIndustri,
    this.latitude,
    this.longitude,
    required this.radiusToleransiMeter,
    required this.jamMasuk,
    required this.jamPulang,
    this.namaPembimbing,
    this.noWaPembimbing,
  });

  factory PlacementModel.fromJson(Map<String, dynamic> json) {
    final industri = json['industri'] as Map<String, dynamic>?;
    final pembimbing = json['guru_pembimbing'] as Map<String, dynamic>?;

    return PlacementModel(
      id: json['id'] ?? 0,
      status: json['status'] ?? 'pending',
      namaIndustri: industri?['nama_industri'],
      alamatIndustri: industri?['alamat'],
      latitude: industri?['latitude'] != null ? double.tryParse(industri!['latitude'].toString()) : null,
      longitude: industri?['longitude'] != null ? double.tryParse(industri!['longitude'].toString()) : null,
      radiusToleransiMeter: industri?['radius_toleransi_meter'] ?? 300,
      jamMasuk: industri?['jam_masuk'] ?? '08:00:00',
      jamPulang: industri?['jam_pulang'] ?? '16:00:00',
      namaPembimbing: pembimbing?['nama'],
      noWaPembimbing: pembimbing?['no_wa'],
    );
  }
}
