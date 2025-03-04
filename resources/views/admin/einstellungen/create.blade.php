@extends('admin.layout')

@section('content')
    <h1>Neue Einstellung anlegen</h1>

    <form action="{{ route('admin.einstellungen.store') }}" method="POST">
        @csrf
        <!-- Beispiel-Felder -->
        <div class="mb-3">
            <label for="name" class="form-label">Einstellungsname</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>

        <div class="mb-3">
            <label for="wert" class="form-label">Wert</label>
            <input type="text" name="wert" id="wert" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
        <a href="{{ route('admin.einstellungen.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
