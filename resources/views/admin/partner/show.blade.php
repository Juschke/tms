@extends('admin.layout')

@section('content')
    <h1>Partner anzeigen</h1>

    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stammdaten des Partners -->
    <div class="mb-3">
        <strong>ID:</strong> {{ $partner->id }}
    </div>
    <div class="mb-3">
        <strong>Name:</strong> {{ $partner->name }}
    </div>
    <div class="mb-3">
        <strong>E-Mail:</strong> {{ $partner->email }}
    </div>
    <div class="mb-3">
        <strong>Rolle:</strong> {{ $partner->role }}
    </div>
    <div class="mb-3">
        <strong>Telefon:</strong> {{ $partner->telefon }}
    </div>
    <div class="mb-3">
        <strong>Kommentar:</strong> 
        @if($partner->kommentar)
            {{ $partner->kommentar }}
        @else
            <em>- kein Kommentar -</em>
        @endif
    </div>
    <div class="mb-3">
        <strong>Erstellt am:</strong> {{ $partner->created_at }}
    </div>

    <hr>

    <!-- Aufträge, die diesem Partner zugewiesen sind -->
    <h3>Verknüpfte Aufträge</h3>
    @if($partner->auftraege->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Auftragsnummer</th>
                    <th>Status</th>
                    <th>Erstellt am</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partner->auftraege as $auftrag)
                    <tr>
                        <td>{{ $auftrag->id }}</td>
                        <td>{{ $auftrag->auftragsnummer }}</td>
                        <td>{{ $auftrag->status }}</td>
                        <td>{{ $auftrag->created_at }}</td>
                        <td>
                            <!-- Beispiel: Anzeige oder Bearbeitung des Auftrags -->
                            <a href="{{ route('admin.auftraege.show', $auftrag->id) }}" 
                               class="btn btn-info btn-sm">
                                Anzeigen
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Keine Aufträge zugewiesen.</p>
    @endif

    <hr>

    <!-- Navigation -->
    <a href="{{ route('admin.partner.index') }}" class="btn btn-secondary me-2">Zurück</a>
    <a href="{{ route('admin.partner.edit', $partner->id) }}" class="btn btn-primary">Bearbeiten</a>
@endsection
