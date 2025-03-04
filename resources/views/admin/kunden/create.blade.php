
@extends('admin.layout')

@section('content')
    <h1>Neuen Kunden anlegen</h1>

    <form action="{{ route('admin.kunden.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="firmenname" class="form-label">Firmenname</label>
            <input type="text" name="firmenname" id="firmenname" class="form-control" value="{{ old('firmenname') }}" required>
        </div>
        <div class="mb-3">
            <label for="kontakt_email" class="form-label">Kontakt-E-Mail</label>
            <input type="email" name="kontakt_email" id="kontakt_email" class="form-control" value="{{ old('kontakt_email') }}" required>
        </div>
        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" name="telefon" id="telefon" class="form-control" value="{{ old('telefon') }}">
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" class="form-control">{{ old('adresse') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="unternehmensname" class="form-label">Unternehmensname</label>
            <input type="text" name="unternehmensname" id="unternehmensname" class="form-control" value="{{ old('unternehmensname') }}">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
        <a href="{{ route('admin.kunden.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
