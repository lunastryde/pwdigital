<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupportThread;

class StaffSupportInboxButton extends Component
{
    public int $openCount = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    public function refreshCount()
    {
        $user = auth()->user();

        if (! $user) {
            $this->openCount = 0;
            return;
        }

        $this->openCount = SupportThread::query()
            ->whereHas('messages') // only threads that have messages (same as inbox)
            ->whereIn('status', ['open', 'pending']) // only active
            ->where(function ($q) use ($user) {
                $q->whereNull('staff_id')         // unassigned
                  ->orWhere('staff_id', $user->id); // or assigned to this staff
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.staff-support-inbox-button');
    }
}
