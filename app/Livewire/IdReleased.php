<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use Illuminate\Support\Facades\DB; // still here, harmless if unused

class IdReleased extends Component
{
    public $items;

    // Filters
    public $selectedYear   = '';
    public $selectedMonth  = '';
    public $selectedType   = ''; // '', 'ID Application', 'Encoded Application'

    // Options for dropdowns
    public $years  = [];
    public $months = [];

    protected $listeners = [
        'application-updated' => 'load',
    ];

    public function mount()
    {
        // Distinct years with finalized IDs
        $this->years = FormPersonal::where('status', 'Finalized')
            ->whereNotNull('date_issued')
            ->selectRaw('YEAR(date_issued) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Distinct months (for initial state: all years)
        $this->refreshMonths();

        $this->load();
    }

    // ---- Livewire hooks ----

    public function updatedSelectedYear()
    {
        // When year changes, refresh month options and reset selected month
        $this->selectedMonth = '';
        $this->refreshMonths();
        $this->load();
    }

    public function updatedSelectedMonth()
    {
        $this->load();
    }

    public function updatedSelectedType()
    {
        $this->load();
    }

    // ---- Helpers ----

    protected function refreshMonths(): void
    {
        $this->months = FormPersonal::where('status', 'Finalized')
            ->whereNotNull('date_issued')
            ->when($this->selectedYear !== '', function ($q) {
                $q->whereYear('date_issued', $this->selectedYear);
            })
            ->selectRaw('MONTH(date_issued) as month')
            ->distinct()
            ->orderBy('month', 'asc')
            ->pluck('month')
            ->toArray();
    }

    public function load()
    {
        $query = FormPersonal::with('files')
            ->where('status', 'Finalized');

        // Year filter
        if ($this->selectedYear !== '') {
            $query->whereYear('date_issued', $this->selectedYear);
        }

        // Month filter
        if ($this->selectedMonth !== '') {
            $query->whereMonth('date_issued', $this->selectedMonth);
        }

        // Application type filter: normal vs encoded
        if ($this->selectedType !== '') {
            $query->where('applicant_type', $this->selectedType);
        }

        $this->items = $query
            ->orderByDesc('date_issued')
            ->get();
    }

    public function render()
    {
        return view('livewire.id-released', [
            'items'  => $this->items,
            'years'  => $this->years,
            'months' => $this->months,
        ]);
    }
}
