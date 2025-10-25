<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;

class IdPreview extends Component
{
    public ?int $applicantId = null;
    public ?FormPersonal $form = null;
    public bool $showModal = false;

    protected $listeners = [
        'openIdPreview' => 'openPreview',
    ];

    public function openPreview(int $applicantId)
    {
        $this->applicantId = $applicantId;
        $this->loadForm();
        $this->showModal = true;
    }

    protected function loadForm(): void
    {
        $this->form = FormPersonal::with('files')->where('applicant_id', $this->applicantId)->first();
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->form = null;
        $this->applicantId = null;
    }

    public function release(): void
    {
        if (! $this->applicantId) return;

        // Send the event to StaffView
        $this->dispatch('releaseApplication', $this->applicantId)->to('staff-view');

        // Close the modal
        $this->close();

        // Optional toast event for UI
        $this->dispatch('toast', message: 'Application released (Finalized).');
    }


    public function render()
    {
        return view('livewire.id-preview');
    }
}
