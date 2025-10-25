<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormPersonal;
use App\Models\FormFile;

class RequirementModal extends Component
{
    public bool $open = false;
    public ?int $selectedId = null;

    protected $listeners = ['open-requirement-modal' => 'openModal'];

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
        $files = null;
        
        if ($this->open && $this->selectedId) {
            $application = FormPersonal::with(['occupation', 'oi', 'refnos', 'guardian'])
                ->where('applicant_id', $this->selectedId)
                ->first();
            
            // Get the files for this application
            $files = FormFile::where('applicant_id', $this->selectedId)->first();
        }
        
        return view('livewire.requirement-modal', [
            'application' => $application,
            'files' => $files,
            'open' => $this->open,
        ]);
    }
}