<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .footer { margin-top: 20px; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <h1>Rechnung</h1>
    
    <p><strong>Absender:</strong> {{ $settings->firmenname }}</p>
    <p>{{ $settings->adresse }}</p>
    
    <p><strong>Empfänger:</strong> {{ $auftrag->kunde->firmenname }}</p>
    <p>{{ $auftrag->kunde->adresse }}</p>

    <p><strong>Rechnungsnummer:</strong> {{ $auftrag->auftragsnummer }}</p>
    <p><strong>Datum:</strong> {{ now()->format('d.m.Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Beschreibung</th>
                <th>Menge</th>
                <th>Einheit</th>
                <th>Preis</th>
                <th>MwSt</th>
                <th>Gesamt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($auftrag->positionen as $position)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $position->beschreibung }}</td>
                    <td>{{ $position->menge }}</td>
                    <td>{{ $position->einheit }}</td>
                    <td>{{ number_format($position->preis, 2, ',', '.') }} €</td>
                    <td>{{ $position->mwst }}%</td>
                    <td>{{ number_format($position->gesamtpreis, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Gesamtbetrag:</strong> {{ number_format($auftrag->gesamtpreis, 2, ',', '.') }} €</p>

    <p class="footer">
        {{ $settings->firmenname }} | {{ $settings->adresse }} | IBAN: {{ $settings->iban }} | BIC: {{ $settings->bic }} | USt-ID: {{ $settings->umsatzsteuer_id }}
    </p>
</body>
</html>
