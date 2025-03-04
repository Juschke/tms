@extends('admin.layout')

@section('content')
    <h1>Neuen Mitarbeiter anlegen</h1>

    <form action="{{ route('admin.mitarbeiter.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="vorname" class="form-label">Vorname</label>
            <input type="text" name="vorname" id="vorname" class="form-control" value="{{ old('vorname') }}" required>
        </div>

        <div class="mb-3">
            <label for="nachname" class="form-label">Nachname</label>
            <input type="text" name="nachname" id="nachname" class="form-control" value="{{ old('nachname') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" name="telefon" id="telefon" class="form-control" value="{{ old('telefon') }}">
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
        <a href="{{ route('admin.mitarbeiter.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
