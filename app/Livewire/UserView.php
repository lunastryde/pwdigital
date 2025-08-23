<?php

namespace App\Livewire;

use Livewire\Component;

class UserView extends Component
{
    /**
     * Currently active tab key.
     * Supported: profile, applications, drafts, notifications
     */
    public string $tab = 'profile';

    protected $queryString = ['tab' => ['except' => 'profile']];

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

    public function render()
    {
        return view('livewire.user-view');
    }
}
