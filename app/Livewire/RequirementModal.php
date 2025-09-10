<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormPersonal;

class RequirementModal extends Component
{
    public bool $open = false;
    public ?int $selectedId = null;

    #[On('open-requirements')]
    public function open(int $id): void
    {
        $this->selectedId = $id;
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
    }
    public function render()
    {
        $application = null;
        if ($this->open && $this->selectedId) {
            $application = FormPersonal::with(['occupation', 'oi', 'refnos', 'guardian'])
                ->where('applicant_id', $this->selectedId)
                ->first();
        }
        return view('livewire.requirement-modal', [
            'application' => $application,
            'open' => $this->open,
        ]);
    }
}
