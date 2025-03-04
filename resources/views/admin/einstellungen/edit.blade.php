@extends('admin.layout')

@section('content')
    <h1>Einstellung bearbeiten</h1>

    <form action="{{ route('admin.einstellungen.update', 1) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Beispieldaten. Nutze hier z. B. $setting->id, $setting->name, etc. -->
        <div class="mb-3">
            <label for="name" class="form-label">Einstellungsname</label>
            <input type="text" name="name" id="name" class="form-control" value="Beispiel-Einstellung">
        </div>

        <div class="mb-3">
            <label for="wert" class="form-label">Wert</label>
            <input type="text" name="wert" id="wert" class="form-control" value="Beispiel-Wert">
        </div>

        <button type="submit" class="btn btn-primary">Aktualisieren</button>
        <a href="{{ route('admin.einstellungen.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
