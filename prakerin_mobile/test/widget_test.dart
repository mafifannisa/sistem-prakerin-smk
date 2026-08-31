import 'package:flutter_test/flutter_test.dart';
import 'package:prakerin_mobile/core/services/location_service.dart';

void main() {
  test('Haversine distance calculation test', () {
    final locationService = LocationService();
    // Koordinat SMKN 3 Tuban ke Alun-Alun Tuban (~1.2 km)
    final distance = locationService.calculateDistance(
      -6.894520,
      112.058340,
      -6.897120,
      112.064500,
    );

    expect(distance, greaterThan(500));
    expect(distance, lessThan(2000));
  });
}
