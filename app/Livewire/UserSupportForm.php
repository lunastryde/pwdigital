<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\FormSupportRenewal;
use App\Models\FormSupportLoss;
use Illuminate\Support\Facades\Auth;

class UserSupportForm extends Component
{
    use WithFileUploads;

    protected $primaryKey = 'request_id';

    public string $tab = 'renewal';

    public $personal;
    public $med_cert_file;
    public $affidavit_file;

    public bool $isBlockedRenewal = false;
    public bool $isBlockedLoss = false;


    public function mount()
    {
        $personal = FormPersonal::where('account_id', Auth::id())->first();

        if (!$personal) {
            return redirect()->route('home', ['tab' => 'applications'])
                ->with('error', 'Please complete your PWD ID application first.');
        }

        $this->personal = $personal;

        // If ID was never issued -> block everything
        if ($personal->status !== 'Finalized') {
            $this->isBlockedRenewal = true;
            $this->isBlockedLoss = true;
            return;
        }

        // If ID issued but still valid -> block only renewal
        $this->isBlockedRenewal = now()->lt($personal->expiration_date);
        $this->isBlockedLoss = false; // Loss is always allowed after issue
    }

    public function switchTab($tab)
    {
        $this->tab = $tab;
        $this->resetValidation();
    }

    public function submitRenewal()
    {
        $this->validate([
            'med_cert_file' => 'required|file|max:10240', // 10MB
        ]);

        try {
            $personal = $this->personal; // already loaded in mount
            $applicantId = $personal->applicant_id;

            // 1) create parent form_requests row
            $formRequest = FormRequest::create([
                'applicant_id' => $applicantId,
                'request_type' => 'renewal',
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            // 2) Save file to same storage pattern as form_personal
            $storagePath = 'form-documents/' . $applicantId;
            $originalName = $this->med_cert_file->getClientOriginalName();
            $fileName = 'file_medcert_' . time() . '_' . $originalName;
            $filePath = $this->med_cert_file->storeAs($storagePath, $fileName, 'public');

            // metadata (keep same structure you used)
            $fileMetadata = [
                'file_medcert' => [
                    'original_name' => $originalName,
                    'size' => $this->med_cert_file->getSize(),
                    'mime_type' => $this->med_cert_file->getClientMimeType(),
                    'extension' => $this->med_cert_file->getClientOriginalExtension(),
                    'uploaded_at' => now()->toISOString(),
                ]
            ];

            // 3) create child row in form_request_renewal
            FormSupportRenewal::create([
                'request_id' => $formRequest->request_id,
                'applicant_id' => $applicantId,
                'file_medcert' => $filePath,
                'file_metadata' => $fileMetadata, // assuming JSON column on child table
                'submitted_at' => now(),
                'status' => 'pending', // child-level status if you use it
            ]);

            // Update parent status so it's clear for staff
            $formRequest->update(['status' => 'Pending']);

            // reset file input
            $this->reset('med_cert_file');

            session()->flash('success', 'Renewal request submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Renewal submission error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while submitting the renewal. Please try again.');
        }
    }

    public function submitLoss()
    {
        $this->validate([
            'affidavit_file' => 'required|file|max:10240', // 10MB
        ]);

        try {
            $personal = $this->personal;
            $applicantId = $personal->applicant_id;

            // 1) create parent form_requests row
            $formRequest = FormRequest::create([
                'applicant_id' => $applicantId,
                'request_type' => 'loss',
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            // 2) Save file
            $storagePath = 'form-documents/' . $applicantId;
            $originalName = $this->affidavit_file->getClientOriginalName();
            $fileName = 'file_affidavit_' . time() . '_' . $originalName;
            $filePath = $this->affidavit_file->storeAs($storagePath, $fileName, 'public');

            $fileMetadata = [
                'file_affidavit' => [
                    'original_name' => $originalName,
                    'size' => $this->affidavit_file->getSize(),
                    'mime_type' => $this->affidavit_file->getClientMimeType(),
                    'extension' => $this->affidavit_file->getClientOriginalExtension(),
                    'uploaded_at' => now()->toISOString(),
                ]
            ];

            // 3) create child row in form_request_loss
            FormSupportLoss::create([
                'request_id' => $formRequest->request_id,
                'applicant_id' => $applicantId,
                'file_affidavit' => $filePath,
                'file_metadata' => $fileMetadata,
                'submitted_at' => now(),
                'status' => 'pending',
            ]);

            // Update parent status
            $formRequest->update(['status' => 'Pending']);

            $this->reset('affidavit_file');

            session()->flash('success', 'Lost ID report submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Loss submission error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while submitting the lost ID report. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user-support-form');
    }
}
