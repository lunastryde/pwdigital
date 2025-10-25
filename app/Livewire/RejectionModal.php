<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormRequest;
use App\Models\FormPersonal;

class RejectionModal extends Component
{
    public bool $open = false;
    public ?int $selectedId = null;
    public ?string $type = null; // 'request' or 'application'
    public string $remarks = '';

    #[On('open-rejection-modal')]
    public function open(int $id, string $type): void
    {
        $this->selectedId = $id;
        $this->type = $type;
        $this->remarks = '';
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset(['selectedId', 'type', 'remarks']);
    }

    public function reject()
    {
        $this->validate([
            'remarks' => 'required|string|min:10|max:500'
        ]);

        try {
            if ($this->type === 'request') {
                $request = FormRequest::find($this->selectedId);
                if ($request) {
                    $request->update([
                        'status' => 'Rejected',
                        'reviewed_by' => auth()->user()->identifier,
                        'reviewed_at' => now(),
                        'remarks' => $this->remarks
                    ]);
                }
            } else {
                $application = FormPersonal::find($this->selectedId);
                if ($application) {
                    $application->update([
                        'status' => 'Rejected',
                        'reviewed_by' => auth()->user()->identifier,
                        'reviewed_at' => now(),
                        'remarks' => $this->remarks
                    ]);
                }
            }

            session()->flash('success', 'Application rejected successfully.');
            $this->close();
            
            // Refresh the parent component to show updated status
            $this->dispatch('application-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject application.');
            \Log::error('Rejection error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.rejection-modal');
    }
}