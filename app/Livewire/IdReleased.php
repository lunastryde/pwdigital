<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use Illuminate\Support\Facades\DB; // Don't forget to import DB

class IdReleased extends Component
{
    public $items;
    public $selectedYear = ''; // Stores the currently selected year
    public $years = [];        // Stores the list of available years for the dropdown

    protected $listeners = [
        'application-updated' => 'load',
    ];

    public function mount()
    {
        // 1. Get all distinct years where IDs were released
        $this->years = FormPersonal::where('status', 'Finalized')
            ->whereNotNull('date_issued')
            ->selectRaw('YEAR(date_issued) as year') // Extract year from date
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $this->load();
    }

    // 2. Livewire Hook: Runs automatically when $selectedYear changes
    public function updatedSelectedYear()
    {
        $this->load();
    }

    public function load()
    {
        $query = FormPersonal::with('files')
            ->where('status', 'Finalized');

        // 3. Apply Year Filter if selected
        if (!empty($this->selectedYear)) {
            $query->whereYear('date_issued', $this->selectedYear);
        }

        $this->items = $query->orderByDesc('date_issued')->get();
    }

    public function render()
    {
        return view('livewire.id-released', [
            'items' => $this->items,
        ]);
    }
}