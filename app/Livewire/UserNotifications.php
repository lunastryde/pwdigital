<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class UserNotifications extends Component
{
    public $notifications = [];
    public $selectedNotification = null;
    public $openModal = false;

    protected $listeners = [
        'refresh-notifications' => 'loadNotifications',
        'close-notification-modal' => 'closeModal',
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        Notification::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();

        Notification::where('is_read', 1)
            ->whereNull('expires_at')
            ->where('created_at', '<', now()->subMonths(3))
            ->delete();

        $this->notifications = Notification::where('account_id', auth()->id())
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function openNotification($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return;
        }

        // Mark as read
        if (!$notification->is_read) {
            $notification->update(['is_read' => 1]);
        }

        $this->selectedNotification = $notification;
        $this->openModal = true;

        // Tell the child modal to open (works even if it's already mounted)
        $this->dispatch('open-notification-modal', $notification->id);

        // Refresh red dot indicator on the parent component (the tabs view)
        // We also dispatch $refresh so the list updates immediately
        $this->dispatch('notifications-read-status-changed');
        $this->dispatch('$refresh');
    }

    public function closeModal()
    {
        $this->openModal = false;
        $this->selectedNotification = null;
    }

    public function render()
    {
        return view('livewire.user-notifications');
    }
}
