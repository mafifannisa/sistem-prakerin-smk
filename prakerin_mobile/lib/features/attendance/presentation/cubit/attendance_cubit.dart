import 'dart:io';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/services/location_service.dart';
import '../../data/datasources/attendance_remote_datasource.dart';
import 'attendance_state.dart';

class AttendanceCubit extends Cubit<AttendanceState> {
  final AttendanceRemoteDataSource _dataSource = AttendanceRemoteDataSource();
  final LocationService _locationService = LocationService();

  AttendanceCubit() : super(AttendanceInitial());

  Future<void> checkCurrentLocation() async {
    emit(AttendanceLoading());
    try {
      final position = await _locationService.getCurrentPosition();
      if (position == null) {
        emit(const AttendanceFailure('Gagal mendapatkan lokasi GPS. Pastikan izin lokasi aktif.'));
        return;
      }

      final result = await _dataSource.checkLocation(
        latitude: position.latitude,
        longitude: position.longitude,
        accuracy: position.accuracy,
      );

      emit(GeofenceChecked(userPosition: position, geofenceResult: result));
    } catch (e) {
      emit(AttendanceFailure(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> submitCheckIn({
    required File selfiePhoto,
    double? livenessScore,
  }) async {
    emit(AttendanceLoading());
    try {
      final position = await _locationService.getCurrentPosition();
      if (position == null) {
        emit(const AttendanceFailure('Gagal membaca koordinat GPS saat presensi.'));
        return;
      }

      await _dataSource.submitCheckIn(
        latitude: position.latitude,
        longitude: position.longitude,
        accuracy: position.accuracy,
        selfiePhoto: selfiePhoto,
        isMockLocation: position.isMocked,
        livenessScore: livenessScore,
      );

      emit(const AttendanceSuccess('Presensi Masuk Berhasil Dicatat!'));
    } catch (e) {
      emit(AttendanceFailure(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> submitCheckOut({
    required File selfiePhoto,
  }) async {
    emit(AttendanceLoading());
    try {
      final position = await _locationService.getCurrentPosition();
      if (position == null) {
        emit(const AttendanceFailure('Gagal membaca koordinat GPS saat presensi pulang.'));
        return;
      }

      await _dataSource.submitCheckOut(
        latitude: position.latitude,
        longitude: position.longitude,
        accuracy: position.accuracy,
        selfiePhoto: selfiePhoto,
        isMockLocation: position.isMocked,
      );

      emit(const AttendanceSuccess('Presensi Pulang Berhasil Dicatat!'));
    } catch (e) {
      emit(AttendanceFailure(e.toString().replaceAll('Exception: ', '')));
    }
  }

  Future<void> submitEmergencyKoreksi({
    required String tanggal,
    required String jenisKoreksi,
    required String jamDiajukan,
    required String alasan,
    File? buktiLampiran,
  }) async {
    emit(AttendanceLoading());
    try {
      await _dataSource.submitEmergencyKoreksi(
        tanggal: tanggal,
        jenisKoreksi: jenisKoreksi,
        jamDiajukan: jamDiajukan,
        alasan: alasan,
        buktiLampiran: buktiLampiran,
      );
      emit(const AttendanceSuccess('Tiket koreksi presensi darurat berhasil dikirim ke Guru Pembimbing!'));
    } catch (e) {
      emit(AttendanceFailure(e.toString().replaceAll('Exception: ', '')));
    }
  }
}
