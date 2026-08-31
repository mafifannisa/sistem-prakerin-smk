import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import '../../../../core/config/app_theme.dart';
import '../../../auth/data/models/user_model.dart';
import '../cubit/attendance_cubit.dart';
import '../cubit/attendance_state.dart';
import 'emergency_koreksi_page.dart';
import 'liveness_camera_page.dart';

class LiveRadarAttendancePage extends StatefulWidget {
  final PlacementModel? placement;
  final bool isCheckOut;

  const LiveRadarAttendancePage({
    super.key,
    required this.placement,
    this.isCheckOut = false,
  });

  @override
  State<LiveRadarAttendancePage> createState() => _LiveRadarAttendancePageState();
}

class _LiveRadarAttendancePageState extends State<LiveRadarAttendancePage> {
  final MapController _mapController = MapController();

  @override
  void initState() {
    super.initState();
    context.read<AttendanceCubit>().checkCurrentLocation();
  }

  @override
  Widget build(BuildContext context) {
    final industriLat = widget.placement?.latitude ?? -6.894520;
    final industriLng = widget.placement?.longitude ?? 112.058340;
    final industriRadius = widget.placement?.radiusToleransiMeter ?? 300;

    return Scaffold(
      appBar: AppBar(
        title: Text(widget.isCheckOut ? 'Presensi Pulang' : 'Presensi Masuk'),
      ),
      body: BlocConsumer<AttendanceCubit, AttendanceState>(
        listener: (context, state) {
          if (state is AttendanceFailure) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.error), backgroundColor: AppTheme.crimson),
            );
          } else if (state is AttendanceSuccess) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.message), backgroundColor: AppTheme.emerald),
            );
            Navigator.pop(context, true);
          }
        },
        builder: (context, state) {
          if (state is AttendanceLoading) {
            return const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(color: AppTheme.emerald),
                  SizedBox(height: 16),
                  Text('Memeriksa posisi satelit GPS & geofence...'),
                ],
              ),
            );
          }

          LatLng userLatLng = LatLng(industriLat, industriLng);
          bool isWithin = false;
          double distanceMeters = 0.0;
          String zoneName = widget.placement?.namaIndustri ?? 'Kantor Magang';

          if (state is GeofenceChecked) {
            userLatLng = LatLng(state.userPosition.latitude, state.userPosition.longitude);
            isWithin = state.geofenceResult.isWithinRadius;
            distanceMeters = state.geofenceResult.nearestDistanceMeters;
            zoneName = state.geofenceResult.zoneName;
          }

          return Column(
            children: [
              // 1. Live Map View
              Expanded(
                flex: 6,
                child: Stack(
                  children: [
                    FlutterMap(
                      mapController: _mapController,
                      options: MapOptions(
                        initialCenter: userLatLng,
                        initialZoom: 16.5,
                      ),
                      children: [
                        TileLayer(
                          urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                          userAgentPackageName: 'com.smkn3tuban.prakerin_mobile',
                        ),
                        // Geofence Circle Radius
                        CircleLayer(
                          circles: [
                            CircleMarker(
                              point: LatLng(industriLat, industriLng),
                              radius: industriRadius.toDouble(),
                              useRadiusInMeter: true,
                              color: AppTheme.emerald.withValues(alpha: 0.18),
                              borderColor: AppTheme.emerald,
                              borderStrokeWidth: 2,
                            ),
                          ],
                        ),
                        // Markers
                        MarkerLayer(
                          markers: [
                            // Office Marker
                            Marker(
                              point: LatLng(industriLat, industriLng),
                              width: 44,
                              height: 44,
                              child: const Icon(
                                Icons.business_rounded,
                                color: AppTheme.primaryNavy,
                                size: 36,
                              ),
                            ),
                            // User Location Marker
                            Marker(
                              point: userLatLng,
                              width: 44,
                              height: 44,
                              child: Icon(
                                Icons.person_pin_circle_rounded,
                                color: isWithin ? AppTheme.emerald : AppTheme.crimson,
                                size: 42,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),

                    // Refresh GPS Button Overlay
                    Positioned(
                      top: 16,
                      right: 16,
                      child: FloatingActionButton.small(
                        backgroundColor: Colors.white,
                        onPressed: () {
                          context.read<AttendanceCubit>().checkCurrentLocation();
                        },
                        child: const Icon(Icons.my_location, color: AppTheme.primaryNavy),
                      ),
                    ),
                  ],
                ),
              ),

              // 2. Status Card & Action Bottom Sheet
              Expanded(
                flex: 5,
                child: Container(
                  padding: const EdgeInsets.all(20),
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black12,
                        blurRadius: 15,
                        offset: Offset(0, -4),
                      ),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Status Badge
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: isWithin ? Colors.green.shade50 : Colors.red.shade50,
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(
                                color: isWithin ? Colors.green.shade300 : Colors.red.shade300,
                              ),
                            ),
                            child: Row(
                              children: [
                                Icon(
                                  isWithin ? Icons.check_circle : Icons.cancel,
                                  size: 16,
                                  color: isWithin ? Colors.green.shade700 : Colors.red.shade700,
                                ),
                                const SizedBox(width: 6),
                                Text(
                                  isWithin ? 'DI DALAM RADIUS' : 'DI LUAR RADIUS',
                                  style: TextStyle(
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                    color: isWithin ? Colors.green.shade800 : Colors.red.shade800,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const Spacer(),
                          Text(
                            'Jarak: ${distanceMeters.toStringAsFixed(0)} Meter',
                            style: const TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 13,
                              color: AppTheme.primaryNavy,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),

                      // Location details
                      Text(
                        zoneName,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppTheme.primaryNavy,
                        ),
                      ),
                      Text(
                        'Batas Maksimal: $industriRadius Meter dari titik kantor',
                        style: const TextStyle(fontSize: 12, color: Colors.grey),
                      ),
                      const Spacer(),

                      // Primary Action Button (Open Liveness Camera)
                      SizedBox(
                        width: double.infinity,
                        height: 52,
                        child: ElevatedButton.icon(
                          onPressed: isWithin
                              ? () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => LivenessCameraPage(
                                        isCheckOut: widget.isCheckOut,
                                      ),
                                    ),
                                  );
                                }
                              : null,
                          icon: const Icon(Icons.camera_front_rounded),
                          label: Text(
                            widget.isCheckOut
                                ? 'VERIFIKASI WAJAH (PULANG)'
                                : 'VERIFIKASI WAJAH (MASUK)',
                          ),
                        ),
                      ),
                      const SizedBox(height: 10),

                      // Emergency Fail-Safe Link
                      Center(
                        child: TextButton.icon(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => EmergencyKoreksiPage(
                                  isCheckOut: widget.isCheckOut,
                                ),
                              ),
                            );
                          },
                          icon: const Icon(Icons.emergency_outlined, size: 16, color: AppTheme.purple),
                          label: const Text(
                            'Kendala GPS / HP? Ajukan Presensi Darurat',
                            style: TextStyle(fontSize: 12, color: AppTheme.purple, fontWeight: FontWeight.w600),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
