class JournalPhotoModel {
  final int id;
  final String url;
  final String? caption;

  JournalPhotoModel({
    required this.id,
    required this.url,
    this.caption,
  });

  factory JournalPhotoModel.fromJson(Map<String, dynamic> json) {
    return JournalPhotoModel(
      id: json['id'] ?? 0,
      url: json['url'] ?? '',
      caption: json['caption'],
    );
  }
}

class JournalModel {
  final int id;
  final String tanggal;
  final int mingguKe;
  final String kegiatan;
  final double durasiJam;
  final String status;
  final String? catatanPembimbing;
  final String? disetujuiOleh;
  final List<JournalPhotoModel> fotos;

  JournalModel({
    required this.id,
    required this.tanggal,
    required this.mingguKe,
    required this.kegiatan,
    required this.durasiJam,
    required this.status,
    this.catatanPembimbing,
    this.disetujuiOleh,
    required this.fotos,
  });

  factory JournalModel.fromJson(Map<String, dynamic> json) {
    final fotosList = (json['fotos'] as List<dynamic>?)
            ?.map((f) => JournalPhotoModel.fromJson(f as Map<String, dynamic>))
            .toList() ??
        [];

    return JournalModel(
      id: json['id'] ?? 0,
      tanggal: json['tanggal'] ?? '',
      mingguKe: json['minggu_ke'] ?? 1,
      kegiatan: json['kegiatan'] ?? '',
      durasiJam: (json['durasi_jam'] as num?)?.toDouble() ?? 0.0,
      status: json['status'] ?? 'pending',
      catatanPembimbing: json['catatan_pembimbing'],
      disetujuiOleh: json['disetujui_oleh'],
      fotos: fotosList,
    );
  }
}
