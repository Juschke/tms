@extends('admin.layout')

@section('content')

<main class="main-content">
    <header class="page-header">
        <h1 class="page-title">Übersetzungsaufträge</h1>
        <a href="{{ route('admin.auftraege.create') }}" class="action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Neuer Auftrag
        </a>
    </header>

    <!-- Dashboard-Karten -->
    <section class="dashboard-cards">
        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">Aktive Aufträge</h3>
                <div class="card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
            </div>
            <div class="card-value">28</div>
            <div class="card-change positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                12% mehr als letzte Woche
            </div>
        </div>

        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">Fällige Aufträge</h3>
                <div class="card-icon orange pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
            </div>
            <div class="card-value">7</div>
            <div class="card-change negative">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                2 mehr als letzte Woche
            </div>
        </div>

        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">Umsatz (Monat)</h3>
                <div class="card-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="card-value">12.580€</div>
            <div class="card-change positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                8% mehr als letzten Monat
            </div>
        </div>

        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">Fertige Aufträge (Monat)</h3>
                <div class="card-icon red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="card-value">42</div>
            <div class="card-change positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                15% mehr als letzten Monat
            </div>
        </div>
    </section>
    <!-- Auswahl-Boxen -->
    <section class="selection-box-container fade-in">
        <div class="selection-box" onclick="location.href='{{ route('admin.auftraege.create') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6
                         a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
            </svg>
            <h3>Auftrag hinzufügen</h3>
        </div>
        <div class="selection-box" onclick="location.href='{{ route('admin.kunden.create') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8
                         a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <h3>Kunde hinzufügen</h3>
        </div>
        <div class="selection-box" onclick="location.href='{{ route('admin.partner.create') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5
                         a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <h3>Partner hinzufügen</h3>
        </div>
    </section>

    <!-- Tabelle mit Livewire -->
    <section class="table-container fade-in p-2">

            <h2 class="table-title mx-1">Aktuelle Übersetzungsprojekte</h2>
            @livewire('auftraege-table')
       
    </section>
</main>

@endsection
