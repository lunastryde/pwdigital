<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class UserApplicationsTab extends Component
{
    // In UserApplicationTab.php
    public function openDetails($applicantId, $type)
    {
        if ($type === 'request') {
            // Dispatch to RequestModal
            $this->dispatch('open-request-details', id: $applicantId);
        } else {
            // Dispatch to RequirementModal
            $this->dispatch('open-requirements', id: $applicantId);
        }
    }

    public function render()
    {
        $userId = Auth::id();

        // ID applications (from FormPersonal)
        $idApps = FormPersonal::where('account_id', $userId)
            ->get()
            ->map(function ($app) {
                return (object) [
                    'id' => $app->applicant_id,  // ✅ ADD THIS
                    'type' => $app->applicant_type ?? 'ID Application',
                    'date' => $app->submitted_at,
                    'status' => $app->status ?? 'pending',
                    'source' => 'personal',
                ];
            });

        // Requests (device / financial / booklet)
        $reqApps = FormRequest::with('applicant')
            ->whereHas('applicant', function ($q) use ($userId) {
                $q->where('account_id', $userId);
            })
            ->get()
            ->map(function ($req) {
                return (object) [
                    'id' => $req->request_id,  // ✅ ADD THIS
                    'type' => ucfirst($req->request_type) . ' Request',
                    'date' => $req->submitted_at ?? $req->created_at,
                    'status' => $req->status ?? 'pending',
                    'source' => 'request',
                ];
            });

        // Merge and sort by date
        $applications = $idApps->merge($reqApps)
            ->sortByDesc('date');

        return view('livewire.user-applications-tab', [
            'applications' => $applications,
        ]);
    }
}
