<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Announcement;
use App\Models\FormPersonal;
use App\Models\FormGuardian;
use Illuminate\Contracts\View\View;

class UserView extends Component
{
    /**
     * Currently active tab key.
     * Supported: profile, applications, drafts, notifications
     */
    public string $tab = 'profile';

    protected $queryString = ['tab' => ['except' => 'profile']];

    public function mount()
    {
        $this->tab = request('tab', 'profile'); // Allows redirect to open specific tab
    }

    /**
     * Switch active tab.
     */
    public function setTab(string $tab): void
    {
        $allowed = ['profile', 'applications', 'drafts', 'notifications'];
        if (in_array($tab, $allowed, true)) {
            $this->tab = $tab;
        }
    }

    public function render(): View
    {
        $user = auth()->user();
        $application = FormPersonal::where('account_id', $user->id)->latest('submitted_at')->first();
        $guardian = null;
        if ($application) {
            $guardian = FormGuardian::where('applicant_id', $application->applicant_id)->first();
        }
        // 1. Fetch all announcements from the database that are currently active.
        $announcements = Announcement::where('is_published', true)
            ->where(function ($query) {
                // This logic ensures we only get announcements that are either...
                // a) Permanent (the expires_at date is not set)
                // b) Not yet expired (the expires_at date is in the future)
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest('published_at') // Order them with the newest one first
            ->get();

        // 2. Return the view and pass the announcements data to it.
        return view('livewire.user-view', [
            'announcements' => $announcements, // <-- This variable now exists for your view
            'application' => $application,
            'guardian' => $guardian,
        ]);
    }
}
