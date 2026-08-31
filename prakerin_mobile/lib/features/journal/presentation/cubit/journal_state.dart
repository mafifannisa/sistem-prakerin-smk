import 'package:equatable/equatable.dart';
import '../../data/models/journal_model.dart';

abstract class JournalState extends Equatable {
  const JournalState();

  @override
  List<Object?> get props => [];
}

class JournalInitial extends JournalState {}

class JournalLoading extends JournalState {}

class JournalLoaded extends JournalState {
  final List<JournalModel> journals;

  const JournalLoaded(this.journals);

  @override
  List<Object?> get props => [journals];
}

class JournalOperationSuccess extends JournalState {
  final String message;

  const JournalOperationSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class JournalError extends JournalState {
  final String error;

  const JournalError(this.error);

  @override
  List<Object?> get props => [error];
}
