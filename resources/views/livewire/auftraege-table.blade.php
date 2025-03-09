<div>

    <!-- =========================================
         OBERER BEREICH: HEAD & FILTER
    ========================================== -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-gradient-primary text-white p-3">
            <!-- Zeile 1: Titel, Suchfeld, Button "Neuer Auftrag" -->
            <div class="row align-items-center mb-2">
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-translate"></i>
                        Übersetzungsaufträge
                    </h4>
                </div>
                <div class="col-12 col-md-6 mt-2 mt-md-0 d-flex justify-content-md-end gap-2">
                    <!-- Suchfeld -->
                    <div class="input-group input-group-sm" style="max-width: 200px;">
                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text"
                               wire:model.debounce.300ms="search"
                               class="form-control border-0"
                               placeholder="Auftrag suchen..."
                               style="box-shadow: none;">
                    </div>

                    <!-- Neu-Button -->
                    <a href="{{ route('admin.auftraege.create') }}"
                       class="btn btn-light btn-sm d-flex align-items-center">
                        <i class="bi bi-plus-lg me-1 text-primary"></i>
                        <span class="text-primary fw-medium">Neuer Auftrag</span>
                    </a>
                </div>
            </div>

            <!-- Zeile 2: Filter, Sprachen-Filter, Bulk-Aktionen -->
            <div class="row align-items-center">
                <!-- LEFT: Bulk-Aktionen & Status-Filter -->
                <div class="col-12 col-md-6 d-flex align-items-center gap-3 mb-2">
                    <!-- Bulk-Aktionen (z. B. Status ändern, löschen) -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light text-primary d-flex align-items-center gap-1"
                                type="button"
                                id="bulkActionDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                {{ count($selectedItems) === 0 ? 'disabled' : '' }}>
                            <i class="bi bi-tools"></i> Bulk-Aktionen
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                   href="#"
                                   wire:click="bulkUpdateStatus('InBearbeitung')"
                                   onclick="event.preventDefault();">
                                    <i class="bi bi-gear-fill text-warning"></i>
                                    Status auf "In Bearbeitung"
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                   href="#"
                                   wire:click="bulkDelete"
                                   onclick="event.preventDefault();">
                                    <i class="bi bi-trash-fill text-danger"></i>
                                    Löschen
                                </a>
                            </li>
                            <!-- weitere Bulk-Aktionen nach Bedarf -->
                        </ul>
                    </div>

                    <!-- Status-Filter -->
                    <div class="btn-group btn-group-sm" role="group">
                        <button wire:click="filterByStatus('')" 
                                class="btn {{ $statusFilter == '' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Alle
                        </button>
                        <button wire:click="filterByStatus('Neu')" 
                                class="btn {{ $statusFilter == 'Neu' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-star-fill me-1"></i> Neu
                        </button>
                        <button wire:click="filterByStatus('InBearbeitung')" 
                                class="btn {{ $statusFilter == 'InBearbeitung' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-gear-fill me-1"></i> In Bearbeitung
                        </button>
                        <button wire:click="filterByStatus('Abgeschlossen')" 
                                class="btn {{ $statusFilter == 'Abgeschlossen' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-check-circle-fill me-1"></i> Abgeschlossen
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Priorität-Filter, Sprach-Filter -->
                <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center gap-2">
                    <!-- Priorität Filter -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1"
                                type="button" id="priorityFilterDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-funnel"></i> Priorität
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="priorityFilterDropdown">
                            <li><a class="dropdown-item" href="#" wire:click="filterByPriority('')">Alle</a></li>
                            <li><a class="dropdown-item" href="#" wire:click="filterByPriority('niedrig')">Niedrig</a></li>
                            <li><a class="dropdown-item" href="#" wire:click="filterByPriority('normal')">Normal</a></li>
                            <li><a class="dropdown-item" href="#" wire:click="filterByPriority('hoch')">Hoch</a></li>
                            <li><a class="dropdown-item" href="#" wire:click="filterByPriority('dringend')">Dringend</a></li>
                        </ul>
                    </div>

                    <!-- Sprachen-Filter (Button + Badges + Modal) -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Button -->
                        <button type="button"
                                class="btn btn-sm btn-light d-flex align-items-center gap-1"
                                data-bs-toggle="modal"
                                data-bs-target="#filterLanguagesModal">
                            <i class="bi bi-globe"></i> Sprachen filtern
                        </button>

                        <!-- Badges für aktive Sprach-Filter -->
                        <div class="d-flex flex-wrap align-items-center gap-1">
                            @foreach($selectedLanguageFilters as $lang)
                                <span class="badge bg-secondary d-flex align-items-center gap-1">
                                    {{ $lang['bez_lang'] ?? 'Unbekannt' }}
                                    <i class="bi bi-x-circle"
                                       style="cursor: pointer;"
                                       wire:click="removeLanguageFilter({{ $lang['id'] }})"></i>
                                </span>
                            @endforeach
                        </div>

                        @if(count($selectedLanguageFilters) > 0)
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                    wire:click="clearLanguageFilters">
                                <i class="bi bi-x"></i> Alle entfernen
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Auswahl Sprachen -->
    <div class="modal fade" id="filterLanguagesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sprachen filtern</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Suchfeld -->
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text"
                               class="form-control"
                               placeholder="Sprache suchen..."
                               wire:model="languageSearchTerm">
                    </div>
                    <!-- Liste Sprachen -->
                    <div class="list-group" style="max-height: 250px; overflow-y: auto;">
                        @forelse($allLanguages as $lang)
                            <label class="list-group-item d-flex align-items-center gap-2">
                                <input class="form-check-input me-1"
                                       type="checkbox"
                                       value="{{ $lang->id }}"
                                       wire:model="tempSelectedLanguages">
                                <span>{{ $lang->bez_lang }}</span>
                            </label>
                        @empty
                            <div class="list-group-item text-muted">
                                Keine Sprachen verfügbar.
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="button"
                            class="btn btn-primary"
                            wire:click="applyLanguageFilter"
                            data-bs-dismiss="modal">
                        Anwenden
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====================================
         TABELLE: AUFTRÄGE
    ===================================== -->
    <div class="card border-0 shadow-sm">
        @if (session()->has('message'))
            <div class="alert alert-success m-3 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('message') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <!-- Aktionen an den Anfang -->
                        <th width="130" class="text-center">Aktionen</th>
                        <!-- Checkbox für Bulk-Selection -->
                        <th width="40" class="text-center">
                            <input type="checkbox"
                                   wire:model="selectAll"
                                   style="cursor: pointer;">
                        </th>
                        <th width="80" class="text-center">Nr.</th>
                        <!-- Status (Badge) -->
                        <th width="120">Status</th>
                        <!-- Titel -->
                        <th>Auftrags-Titel</th>
                        <!-- Sprachen -->
                        <th width="150">Sprachen</th>
                        <!-- Kunde -->
                        <th width="150">Kunde</th>
                        <!-- Termine & Finanzen -->
                        <th width="180">Fälligkeit & Finanzen</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Klassennamen für die Hintergrundfarbe nach Priorität
                        $priorityRowBg = [
                            'niedrig'   => 'table-success',
                            'normal'    => 'table-warning',
                            'hoch'      => 'table-danger',
                            'dringend'  => 'table-dark',
                        ];
                        // Status-Infos
                        $statusMap = [
                            'Neu'           => ['text' => 'Neu', 'icon' => 'bi-star-fill',        'color' => 'text-primary'],
                            'InBearbeitung' => ['text' => 'In Bearbeitung','icon' => 'bi-gear-fill','color' => 'text-warning'],
                            'Abgeschlossen' => ['text' => 'Abgeschlossen','icon' => 'bi-check-circle-fill','color' => 'text-success'],
                            'Storniert'     => ['text' => 'Storniert',    'icon' => 'bi-x-circle-fill','color' => 'text-danger'],
                        ];
                    @endphp

                    @forelse($auftraege as $auftrag)
                        @php
                            // Bestimme Zeilen-Hintergrund anhand der Priorität
                            $prioKey   = strtolower($auftrag->prioritaet);
                            $rowClass  = $priorityRowBg[$prioKey] ?? 'table-secondary';

                            // Hole Daten für die Status-Darstellung
                            $st    = $statusMap[$auftrag->status] ?? ['text' => $auftrag->status, 'icon' => 'bi-question-circle-fill', 'color' => 'text-secondary'];
                            $isOverdue = $auftrag->faellig_am && strtotime($auftrag->faellig_am) < time();
                        @endphp

                        <!-- HAUPT-ZEILE -->
                        <tr class="{{ $rowClass }}">
                            <!-- Aktionen -->
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <!-- Bearbeiten -->
                                    <a href="{{ route('admin.auftraege.edit', $auftrag->id) }}"
                                       class="btn btn-outline-primary btn-sm"
                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="Bearbeiten">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <!-- Details -->
                                    <a href="{{ route('admin.auftraege.show', $auftrag->id) }}"
                                       class="btn btn-outline-info btn-sm"
                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="Details">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <!-- Löschen -->
                                    <button class="btn btn-outline-danger btn-sm"
                                            wire:click="deleteSingle({{ $auftrag->id }})"
                                            onclick="confirm('Diesen Auftrag wirklich löschen?') || event.stopImmediatePropagation()"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Löschen">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                                <!-- Notizen-Button -->
                                <div class="d-flex justify-content-center mt-1">
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm w-100"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#notesModal{{ $auftrag->id }}">
                                        <i class="bi bi-card-text me-1"></i> Notizen
                                    </button>
                                </div>
                                
                                <!-- Notizen-Modal -->
                                <div class="modal fade" id="notesModal{{ $auftrag->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Notizen: {{ $auftrag->auftragsnummer }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <textarea class="form-control" rows="5"
                                                          wire:model.lazy="anmerkungen.{{ $auftrag->id }}"
                                                          placeholder="Notizen eingeben..."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                    Schließen
                                                </button>
                                                <button type="button"
                                                        class="btn btn-primary"
                                                        wire:click="saveNote({{ $auftrag->id }})"
                                                        data-bs-dismiss="modal">
                                                    <i class="bi bi-save me-1"></i> Speichern
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Checkbox -->
                            <td class="text-center">
                                <input type="checkbox"
                                       wire:model="selectedItems"
                                       value="{{ $auftrag->id }}"
                                       style="cursor: pointer;">
                            </td>

                            <!-- Nr. -->
                            <td class="text-center">
                                <span class="badge bg-dark">
                                    {{ $auftrag->auftragsnummer }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm w-100 d-flex align-items-center justify-content-between"
                                            style="background-color: #f8f9fa;"
                                            type="button"
                                            id="statusDropdown{{ $auftrag->id }}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="{{ $st['color'] }} d-flex align-items-center gap-1">
                                            <i class="bi {{ $st['icon'] }}"></i>
                                            <span>{{ $st['text'] }}</span>
                                        </span>
                                        <i class="bi bi-caret-down-fill text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $auftrag->id }}">
                                        @foreach($statusMap as $statusKey => $statusVal)
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="#"
                                                   wire:click="updateStatus({{ $auftrag->id }}, '{{ $statusKey }}')">
                                                    <i class="bi {{ $statusVal['icon'] }} {{ $statusVal['color'] }}"></i>
                                                    <span>{{ $statusVal['text'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                            <!-- Titel + Kleinigkeiten -->
                            <td>
                                <div class="fw-bold mb-1">
                                    {{ $auftrag->titel ?? 'Kein Titel' }}
                                </div>
                                <div class="small text-secondary">
                                    @if($auftrag->erstellungsdatum)
                                        <i class="bi bi-calendar-date me-1"></i>
                                        Erstellt: {{ \Carbon\Carbon::parse($auftrag->erstellungsdatum)->format('d.m.Y') }}
                                    @else
                                        <i class="bi bi-calendar-date me-1"></i>
                                        Erstellt: {{ optional($auftrag->created_at)->format('d.m.Y') }}
                                    @endif
                                </div>
                                <!-- Angerufen Switch -->
                                <div class="form-check form-switch small mt-1">
                                    <input class="form-check-input" type="checkbox"
                                           wire:model="angerufen.{{ $auftrag->id }}"
                                           id="angerufen{{ $auftrag->id }}">
                                    <label class="form-check-label" for="angerufen{{ $auftrag->id }}">
                                        Angerufen
                                    </label>
                                </div>
                            </td>

                            <!-- Sprachen -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <!-- Quell-Sprache -->
                                    @if($auftrag->quell_sprache)
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-info">Von</span>
                                            <span class="fi fi-gr"></span> <span class="fi fi-gr fis"></span>
                                            <!--<img src="/flags/{{ strtolower($auftrag->quell_sprache->iso_code ?? 'xx') }}.svg"
                                                 alt="Flagge"
                                                 style="width: 18px;">-->
                                            <span class="small">{{ $auftrag->quell_sprache}}</span>
                                        </div>
                                    @endif
                                    <!-- Ziel-Sprache -->
                                    @if($auftrag->ziel_sprache)
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-primary">Nach</span>
                                            <img src="/flags/{{ strtolower($auftrag->ziel_sprache->iso_code ?? 'xx') }}.svg"
                                                 alt="Flagge"
                                                 style="width: 18px;">
                                            <span class="small">{{ $auftrag->ziel_sprache}}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Kunde -->
                            <td>
                                @if($auftrag->kunde)
                                    <div class="fw-semibold">{{ $auftrag->kunde->firmenname }}</div>
                                    <div class="text-muted small">
                                        {{ $auftrag->kunde->adresse }}
                                    </div>
                                @else
                                    <span class="text-muted small">kein Kunde</span>
                                @endif
                            </td>

                            <!-- Fälligkeit & Finanzen -->
                            <td>
                                <!-- Fälligkeitsdatum -->
                                @if($auftrag->faellig_am)
                                    <div class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-dark' }}">
                                        <i class="bi {{ $isOverdue ? 'bi-exclamation-circle' : 'bi-calendar-check' }} me-1"></i>
                                        Fällig: {{ \Carbon\Carbon::parse($auftrag->faellig_am)->format('d.m.Y') }}
                                    </div>
                                @endif
                                <!-- Preis + Anzahlung -->
                                @if($auftrag->preis_gesamt)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cash-stack text-success me-1"></i>
                                        <strong>
                                            {{ number_format($auftrag->preis_gesamt, 2, ',', '.') }} €
                                        </strong>
                                        @if($auftrag->anzahlung)
                                            <span class="badge bg-info ms-2">
                                                {{ number_format($auftrag->anzahlung, 2, ',', '.') }} € Anzahlung
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <!-- Rabatt -->
                                @if($auftrag->rabatt_prozent)
                                    <div class="text-danger small">
                                        <i class="bi bi-percent me-1"></i>
                                        {{ $auftrag->rabatt_prozent }}% Rabatt
                                    </div>
                                @endif
                            </td>
                        </tr>

                        <!-- ZUSATZ-INFOS in zweiter Zeile -->
                        <tr class="{{ $rowClass }}">
                            <td colspan="8" class="p-2">
                                <div class="small text-secondary d-flex flex-wrap gap-3 align-items-center">
                                    <!-- Partner -->
                                    @if($auftrag->partner_id)
                                        <div>
                                            <strong>Partner:</strong>
                                            {{ $auftrag->partner_id }}
                                        </div>
                                    @endif

                                    <!-- Standort -->
                                    @if($auftrag->standort)
                                        <div>
                                            <strong>Standort:</strong>
                                            {{ $auftrag->standort }}
                                        </div>
                                    @endif

                                    <!-- Hochgeladene Datei -->
                                    @if($auftrag->hochgeladene_datei)
                                        <div>
                                            <strong>Datei:</strong>
                                            <a href="{{ Storage::url($auftrag->hochgeladene_datei) }}" target="_blank">
                                                {{ basename($auftrag->hochgeladene_datei) }}
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Gelöscht markiert -->
                                    @if($auftrag->geloescht_markiert)
                                        <div class="text-danger">
                                            <strong>Gelöscht markiert:</strong>
                                            Ja
                                        </div>
                                    @endif

                                    <!-- Deleted_at -->
                                    @if($auftrag->deleted_at)
                                        <div class="text-danger">
                                            <strong>Deleted at:</strong>
                                            {{ \Carbon\Carbon::parse($auftrag->deleted_at)->format('d.m.Y H:i') }}
                                        </div>
                                    @endif

                                    <!-- Optional: Übersetzer (wenn gewünscht hier) -->
                                    @if($auftrag->users)
                                        <div>
                                            <strong>Übersetzer:</strong>
                                            {{ $auftrag->users->name }}
                                        </div>
                                    @endif

                                    <!-- Anmerkungen -->
                                    @if($auftrag->anmerkungen)
                                        <div class="text-wrap" style="max-width: 350px;">
                                            <strong>Anmerkungen:</strong>
                                            {{ $auftrag->anmerkungen }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-6 d-block mb-3"></i>
                                    <p class="mb-1">Keine Aufträge gefunden.</p>
                                    <p class="small">
                                        Erstellen Sie einen neuen Auftrag oder ändern Sie Ihre Filterkriterien.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ====================================
             PAGINATION / FOOTER
        ===================================== -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 text-secondary small" for="perPageSelect">Anzeigen:</label>
                <select class="form-select form-select-sm w-auto" wire:model="perPage" id="perPageSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-secondary small ms-2">
                    {{ $auftraege->firstItem() ?? 0 }}–{{ $auftraege->lastItem() ?? 0 }}
                    von {{ $auftraege->total() }} Aufträgen
                </span>
            </div>
            <div>
                {{ $auftraege->links() }}
            </div>
        </div>
    </div>

</div>
<script>

    
</script>
