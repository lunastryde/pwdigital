<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class NotificationModal extends Component
{
    public $open = false;
    public $notification = null;
    public $reference = null;
    
    protected $listeners = [
        'open-notification-modal' => 'openModal',
    ];

    public function mount(int $notificationId = null)
    {
        if ($notificationId) {
            $this->openModal($notificationId);
        }
    }

    public function openModal(int $notificationId): void
    {
        $this->notification = Notification::find($notificationId);
        $this->reference = null;

        if (!$this->notification) {
            $this->open = false;
            return;
        }

        if (!$this->notification->is_read) {
            $this->notification->update(['is_read' => 1]);
        }

        if ($this->notification->reference_type === 'form_personal') {
            $this->reference = FormPersonal::find($this->notification->reference_id);
        } elseif (in_array($this->notification->reference_type, ['form_request', 'form_requests'], true)) {
            $this->reference = FormRequest::find($this->notification->reference_id);
        }

        $this->open = true;
    }

    public function close()
    {
        $this->open = false;
        $this->dispatch('close-notification-modal');
    }

    public function render()
    {
        return view('livewire.notification-modal');
    }
}

