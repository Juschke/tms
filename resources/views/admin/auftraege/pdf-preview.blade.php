<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Auftrag Vorschau</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Auftrag Vorschau</h1>
    <p><strong>Auftragsnummer:</strong> {{ $data['auftragsnummer'] ?? 'N/A' }}</p>
    <p><strong>Titel:</strong> {{ $data['titel'] ?? 'N/A' }}</p>
    <p><strong>Fälligkeitsdatum:</strong> {{ $data['faellig_am'] ?? 'N/A' }}</p>
    <p><strong>Kunde:</strong> {{ $data['kunde_id'] ?? 'N/A' }}</p>
    <p><strong>Gesamtpreis:</strong> {{ $data['preis_gesamt'] ?? '0.00' }} €</p>
</body>
</html>
