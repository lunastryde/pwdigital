<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class IdPreview extends Component
{
    use WithFileUploads;

    public ?int $applicantId = null;
    public ?FormPersonal $form = null;
    public bool $showModal = false;

    // Mayor settings
    public string $mayorName = 'ATTY. DOY C. LEACHON';
    public string $mayorTitle = 'City Mayor';
    public string $mayorSignaturePath = 'id-signatures/mayor-signature.png'; // relative to storage/app/public
    public bool $showMayorEditor = false;
    public $newSignature; // temporary upload

    protected string $settingsFile = 'pdao_id_settings.json';

    protected $listeners = [
        'openIdPreview' => 'openPreview',
    ];

    protected $rules = [
        'mayorName'    => 'required|string|max:150',
        'mayorTitle'   => 'nullable|string|max:150',
        'newSignature' => 'nullable|image|max:2048', // 2MB
    ];

    public function openPreview(int $applicantId)
    {
        $this->applicantId = $applicantId;
        $this->loadForm();
        $this->loadMayorSettings();
        $this->showModal = true;
    }

    protected function loadForm(): void
    {
        $this->form = FormPersonal::with('files')
            ->where('applicant_id', $this->applicantId)
            ->first();
    }

    protected function loadMayorSettings(): void
    {
        $path = storage_path('app/' . $this->settingsFile);

        if (File::exists($path)) {
            $data = json_decode(File::get($path), true) ?: [];

            $this->mayorName         = $data['mayor_name']     ?? $this->mayorName;
            $this->mayorTitle        = $data['mayor_title']    ?? $this->mayorTitle;
            $this->mayorSignaturePath = $data['signature_path'] ?? $this->mayorSignaturePath;
        }
    }

    public function saveMayorSettings(): void
    {
        $this->validate();

        $signaturePath = $this->mayorSignaturePath;

        // If new signature uploaded, store/overwrite as a fixed filename
        if ($this->newSignature) {
            $signaturePath = $this->newSignature->storeAs(
                'id-signatures',
                'mayor-signature.png',
                'public' // storage/app/public
            );
        }

        $data = [
            'mayor_name'     => $this->mayorName,
            'mayor_title'    => $this->mayorTitle,
            'signature_path' => $signaturePath,
        ];

        File::put(
            storage_path('app/' . $this->settingsFile),
            json_encode($data, JSON_PRETTY_PRINT)
        );

        $this->mayorSignaturePath = $signaturePath;
        $this->showMayorEditor = false;
        $this->newSignature = null;

        session()->flash('success', 'Mayor details updated.');
    }

    public function getMayorSignatureUrlProperty(): string
    {
        return asset('storage/' . $this->mayorSignaturePath);
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->form = null;
        $this->applicantId = null;
        $this->showMayorEditor = false;
        $this->newSignature = null;
    }

    public function release(): void
    {
        if (!$this->applicantId) return;

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
