@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Einstellungen</h1>

    <form id="einstellungenForm" action="{{ route('admin.einstellungen.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="sap-card">
            <h2 class="sap-card-title"><i class="bi bi-gear"></i> Unternehmensdetails</h2>

            <div class="sap-form-group">
                <label for="firmenname" class="sap-label">Firmenname</label>
                <input type="text" class="sap-form-control" id="firmenname" name="firmenname" value="{{ old('firmenname', $settings->firmenname ?? '') }}">
            </div>

            <div class="sap-form-group">
                <label for="adresse" class="sap-label">Adresse</label>
                <input type="text" class="sap-form-control" id="adresse" name="adresse" value="{{ old('adresse', $settings->adresse ?? '') }}">
            </div>

            <div class="sap-form-group">
                <label for="iban" class="sap-label">IBAN</label>
                <input type="text" class="sap-form-control" id="iban" name="iban" value="{{ old('iban', $settings->iban ?? '') }}">
            </div>

            <div class="sap-form-group">
                <label for="bic" class="sap-label">BIC</label>
                <input type="text" class="sap-form-control" id="bic" name="bic" value="{{ old('bic', $settings->bic ?? '') }}">
            </div>

            <div class="sap-form-group">
                <label for="umsatzsteuer_id" class="sap-label">Umsatzsteuer-ID</label>
                <input type="text" class="sap-form-control" id="umsatzsteuer_id" name="umsatzsteuer_id" value="{{ old('umsatzsteuer_id', $settings->umsatzsteuer_id ?? '') }}">
            </div>
        </div>

        <button type="submit" class="sap-btn"><i class="bi bi-save"></i> Einstellungen speichern</button>
    </form>
</div>
@endsection
