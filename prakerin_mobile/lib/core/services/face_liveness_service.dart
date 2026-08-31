import 'package:google_mlkit_face_detection/google_mlkit_face_detection.dart';

enum LivenessChallenge {
  blink,
  smile,
  turnHeadLeft,
}

class FaceLivenessService {
  late final FaceDetector _faceDetector;
  
  bool _isEyeClosed = false;
  int blinkCount = 0;
  bool isSmileDetected = false;
  bool isHeadTurned = false;

  FaceLivenessService() {
    final options = FaceDetectorOptions(
      enableClassification: true, // Untuk deteksi kedip dan senyum
      enableTracking: true,
      performanceMode: FaceDetectorMode.fast,
      minFaceSize: 0.15,
    );
    _faceDetector = FaceDetector(options: options);
  }

  FaceDetector get detector => _faceDetector;

  /// Memproses frame wajah untuk mengevaluasi tantangan liveness
  bool evaluateFrame(Face face, LivenessChallenge challenge) {
    switch (challenge) {
      case LivenessChallenge.blink:
        final leftOpen = face.leftEyeOpenProbability ?? 1.0;
        final rightOpen = face.rightEyeOpenProbability ?? 1.0;

        if (leftOpen < 0.25 && rightOpen < 0.25) {
          _isEyeClosed = true;
        } else if (leftOpen > 0.75 && rightOpen > 0.75 && _isEyeClosed) {
          blinkCount++;
          _isEyeClosed = false;
        }
        return blinkCount >= 2;

      case LivenessChallenge.smile:
        final smileProb = face.smilingProbability ?? 0.0;
        if (smileProb > 0.7) {
          isSmileDetected = true;
        }
        return isSmileDetected;

      case LivenessChallenge.turnHeadLeft:
        final headEulerY = face.headEulerAngleY ?? 0.0;
        if (headEulerY > 18.0) {
          isHeadTurned = true;
        }
        return isHeadTurned;
    }
  }

  void reset() {
    _isEyeClosed = false;
    blinkCount = 0;
    isSmileDetected = false;
    isHeadTurned = false;
  }

  void dispose() {
    _faceDetector.close();
  }
}
