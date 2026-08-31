import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'core/config/app_theme.dart';
import 'core/services/storage_service.dart';
import 'features/auth/presentation/cubit/auth_cubit.dart';
import 'features/attendance/presentation/cubit/attendance_cubit.dart';
import 'features/journal/presentation/cubit/journal_cubit.dart';
import 'features/auth/presentation/pages/login_page.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await StorageService().init();
  await initializeDateFormatting('id_ID', null);

  runApp(const PrakerinMobileApp());
}

class PrakerinMobileApp extends StatelessWidget {
  const PrakerinMobileApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (_) => AuthCubit()..checkAuthStatus()),
        BlocProvider(create: (_) => AttendanceCubit()),
        BlocProvider(create: (_) => JournalCubit()),
      ],
      child: MaterialApp(
        title: 'Prakerin SMKN 3 Tuban',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        home: const LoginPage(),
      ),
    );
  }
}
