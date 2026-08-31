import 'package:equatable/equatable.dart';
import 'package:geolocator/geolocator.dart';
import '../../data/models/attendance_model.dart';

abstract class AttendanceState extends Equatable {
  const AttendanceState();

  @override
  List<Object?> get props => [];
}

class AttendanceInitial extends AttendanceState {}

class AttendanceLoading extends AttendanceState {}

class GeofenceChecked extends AttendanceState {
  final Position userPosition;
  final GeofenceCheckResult geofenceResult;

  const GeofenceChecked({
    required this.userPosition,
    required this.geofenceResult,
  });

  @override
  List<Object?> get props => [userPosition, geofenceResult];
}

class AttendanceSuccess extends AttendanceState {
  final String message;

  const AttendanceSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class AttendanceFailure extends AttendanceState {
  final String error;

  const AttendanceFailure(this.error);

  @override
  List<Object?> get props => [error];
}
