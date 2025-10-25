<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;

class IdReleased extends Component
{
    public $items;

    protected $listeners = [
        'application-updated' => 'load',
    ];

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $this->items = FormPersonal::with('files')
            ->where('status', 'Finalized')
            ->orderByDesc('date_issued')
            ->get();
    }

    public function render()
    {
        return view('livewire.id-released', [
            'items' => $this->items,
        ]);
    }
}

