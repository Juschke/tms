@extends('admin.layout')

@section('content')
    <h1>Details zur Einstellung</h1>

    <p><strong>Name:</strong> Beispiel-Einstellung</p>
    <p><strong>Wert:</strong> Beispiel-Wert</p>

    <a href="{{ route('admin.einstellungen.index') }}" class="btn btn-secondary">Zurück</a>
@endsection
