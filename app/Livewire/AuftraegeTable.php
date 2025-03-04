<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auftrag;
use Carbon\Carbon;

class AuftraegeTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFilter = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $selectAll = false;
    public $selectedItems = [];
    public $perPage = 10;
    public $anmerkungen = [];
    public $angerufen = [];
    
    protected $listeners = ['refreshTable' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Auftrag::query()
            ->when($this->search, function($query) {
                return $query->where(function($query) {
                    $query->where('auftragsnummer', 'like', '%' . $this->search . '%')
                          ->orWhere('titel', 'like', '%' . $this->search . '%')
                          ->orWhereHas('kunde', function($query) {
                              $query->where('firmenname', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->statusFilter, function($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);
            
        $auftraege = $query->paginate($this->perPage);
            
        return view('livewire.auftraege-table', [
            'auftraege' => $auftraege
        ]);
    }

    protected function getAuftraegeQuery()
    {
        $query = Auftrag::with('kunde', 'benutzer');

        if (!empty($this->search)) {
            $searchTerm = $this->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('auftragsnummer', 'LIKE', '%'.$searchTerm.'%')
                  ->orWhereHas('kunde', function ($subQuery) use ($searchTerm) {
                      $subQuery->where('firmenname', 'LIKE', '%'.$searchTerm.'%');
                  });
            });
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }


    public function updateStatus($auftragId, $newStatus)
    {
        $validStatuses = ['Neu', 'InBearbeitung', 'Abgeschlossen', 'Storniert'];

        if (!in_array($newStatus, $validStatuses)) {
            session()->flash('error', 'Ungültiger Status.');
            return;
        }

        $auftrag = Auftrag::find($auftragId);
        if ($auftrag) {
            $auftrag->update(['status' => $newStatus]);
            $this->render();
            session()->flash('message', 'Status erfolgreich aktualisiert.');
        }
    }

    public function toggleAngerufen($auftragId)
    {
        $auftrag = Auftrag::find($auftragId);
        if ($auftrag) {
            $auftrag->update(['angerufen' => !$auftrag->angerufen]);
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getAuftraegeQuery()
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function bulkDelete()
    {
        if (count($this->selectedItems) > 0) {
            Auftrag::whereIn('id', $this->selectedItems)->delete();
            $this->reset('selectedItems', 'selectAll');
            session()->flash('message', 'Die ausgewählten Aufträge wurden gelöscht.');
            $this->render();
        }
    }

    public function deleteSingle($id)
    {
        $auftrag = Auftrag::findOrFail($id);
        $auftrag->delete();
        session()->flash('message', "Auftrag mit der ID {$id} wurde gelöscht.");
        $this->render();
    }

    public function printAuftrag($auftragId)
    {
        // Hier kannst du die Logik zur PDF-Generierung oder zum Drucken einfügen
        session()->flash('message', "Druckauftrag für ID {$auftragId} gestartet.");
    }

    public function formatDate($date)
    {
        return optional(Carbon::parse($date))->format('d.m.Y');
    }

      
    public function mount()
    {
        $this->loadAuftragData();
    }
    
    public function loadAuftragData()
    {
        $auftraege = Auftrag::all();
        
        foreach ($auftraege as $auftrag) {
            $this->anmerkungen[$auftrag->id] = $auftrag->anmerkungen ?? '';
            $this->angerufen[$auftrag->id] = $auftrag->angerufen ? true : false;
        }
    }
    
    public function updated($field)
    {
        // Wenn ein Angerufen-Checkbox geändert wurde
        if (strpos($field, 'angerufen.') === 0) {
            $auftragId = str_replace('angerufen.', '', $field);
            $this->saveAngerufen($auftragId);
        }
    }
    
    public function saveAngerufen($id)
    {
        $auftrag = Auftrag::find($id);
        if ($auftrag) {
            $auftrag->angerufen = $this->angerufen[$id] ?? false;
            $auftrag->save();
        }
    }
    
    public function saveNote($id)
    {
        $auftrag = Auftrag::find($id);
        if ($auftrag) {
            $auftrag->anmerkungen = $this->anmerkungen[$id] ?? '';
            $auftrag->save();

        }
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    

    
    public function updatePriority($id, $priority)
    {
        $auftrag = Auftrag::find($id);
        if ($auftrag) {
            $auftrag->prioritaet = $priority;
            $auftrag->save();
        }
    }
    
    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    

    
}
