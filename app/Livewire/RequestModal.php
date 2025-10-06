<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class RequestModal extends Component
{
    public bool $open = false;
    public ?int $selectedId = null;

    #[On('open-request-details')]
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
        $request = null;

        if ($this->open && $this->selectedId) {
            $request = FormRequest::with([
                'applicant',
                'deviceRequest',
                'bookletRequest',
                'financialRequest',
            ])->find($this->selectedId);
        }

        return view('livewire.request-modal', [
            'request' => $request,
            'open' => $this->open,
        ]);
    }
}
