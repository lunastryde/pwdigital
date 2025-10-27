<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\FormPersonal;
use App\Models\FormOccupation;
use App\Models\FormOi;
use App\Models\FormRefnos;
use App\Models\FormGuardian;
use App\Models\FormFile;

class UserForm extends Component
{
    use WithFileUploads;
    /**
     * Current step of the form (1-based).
     */
    public int $step = 1;

    // Step 1: Personal Information + Disability
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $suffix = '';
    public ?string $date_of_birth = null;
    public string $blood_type = '';
    public string $age = '';

    public string $civil_status = '';
    public string $gender = '';

    // Contact details
    public string $mobile_no = '';
    public string $email = '';
    public string $landline_no = '';

    // Address
    public string $house_no = '';
    public string $street = '';
    public string $barangay = '';
    public string $municipality = '';
    public string $province = '';
    // removed region field from UI

    // Disability info (moved to Step 1 UI)
    public array $disability_types = [];
    public array $disability_causes = [];

    // Step 2: Education, Employment, Organization, IDs
    public string $educational_attainment = '';
    public string $employment_type = '';
    public string $employment_status = '';
    public string $employment_category = '';
    public string $occupation = '';
    public string $occupation_other = '';
    public string $four_ps_member = ''; // YES / NO

    // Organization Information
    public string $org_affiliated = '';
    public string $org_contact_person = '';
    public string $org_contact_no = '';
    public string $org_house_no = '';
    public string $org_street = '';
    public string $org_brgy = '';
    public string $org_municipality = '';
    public string $org_province = '';

    // ID Reference
    public string $id_sss = '';
    public string $id_gsis = '';
    public string $id_philhealth = '';
    public string $id_others = '';
    public string $id_pagibig = '';

    // Step 3: Family Background
    // Family background
    public string $father_last = '';
    public string $father_first = '';
    public string $father_middle = '';
    public string $father_contact = '';

    public string $mother_last = '';
    public string $mother_first = '';
    public string $mother_middle = '';
    public string $mother_contact = '';

    public string $spouse_last = '';
    public string $spouse_first = '';
    public string $spouse_middle = '';
    public string $spouse_contact = '';

    // Physician
    public string $physician_name = '';

    // File upload properties
    public $uploadedFiles = [];
    public $fileRecord = null;
    public $files = [
        'id_picture' => null,
        'psa' => null,
        'cert_of_disability' => null,
        'med_cert' => null,
        'endorsement_letter' => null
    ];

    /**
     * Go to next step (no validation yet; purely UI).
     */
    public function nextStep(): void
    {
        if ($this->step < 3) {
            $this->step++;
        }
        $this->dispatch('scroll-to-top');
    }

    /**
     * Go back to previous step.
     */
    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
        $this->dispatch('scroll-to-top');
    }

    public function validateFile($file)
    {
        $maxSize = 10 * 1024 * 1024; // 10MB
        $allowedTypes = [
            'application/pdf', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            'image/jpeg', 
            'image/jpg', 
            'image/png'
        ];
        
        return $file->getSize() <= $maxSize && in_array($file->getMimeType(), $allowedTypes);
    }

    public function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getUploadedCount()
    {
        return count(array_filter($this->files));
    }

    public function allFilesUploaded()
    {
        return $this->getUploadedCount() === 5;
    }

    public function getMissingFilesCount()
    {
        return 5 - $this->getUploadedCount();
    }

    public function removeFile($fileType)
    {
        $this->files[$fileType] = null;
    }

    protected function rules()
    {
        return [
            'files.id_picture' => 'nullable|file|max:10240|mimes:jpg,jpeg,png',
            'files.psa' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'files.cert_of_disability' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'files.med_cert' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'files.endorsement_letter' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }

    /**
     * Persist the application across related tables.
     */
    public function submit(): void
    {
        // Basic validation for required fields present in the UI
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'barangay' => 'required|string|max:100',
            'municipality' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            // Step 2 (some can be optional)
            'educational_attainment' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string|max:100',
            'employment_status' => 'nullable|string|max:100',
            'employment_category' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:150',
        ]);

        $account = Auth::user();
        if (!$account) {
            $this->addError('auth', 'You must be logged in to submit an application.');
            return;
        }

        // Generate 8-digit PWD number
        $pwdNumber = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

        // Compose disability strings (DB columns are VARCHAR)
        $disabilityType = empty($this->disability_types) ? null : implode(', ', $this->disability_types);
        $disabilityCause = empty($this->disability_causes) ? null : implode(', ', $this->disability_causes);

        // 1) form_personal
        $personal = FormPersonal::create([
            // Foreign keys / linkage
            'account_id'     => $account->id,             // explicit FK to accounts_master

            // Account snapshot
            'email'          => $this->email ?: ($account->email ?? ''),

            // Meta
            'status'         => 'Pending',
            'applicant_type' => 'ID Application',
            'pwd_number'     => $pwdNumber,
            'submitted_at'   => now(),

            // Personal details
            'fname'          => $this->first_name,
            'mname'          => $this->middle_name ?: null,
            'lname'          => $this->last_name,
            'suffix'         => $this->suffix ?: null,
            'birthdate'      => $this->date_of_birth,
            'age'            => $this->age ?: null,
            'sex'            => $this->gender,
            'bloodtype'      => $this->blood_type ?: null,
            'civil_status'   => $this->civil_status,
            'disability_type'=> $disabilityType,
            'disability_cause'=> $disabilityCause,

            // Address & contact
            'house_no'       => $this->house_no ?: null,
            'street'         => $this->street ?: null,
            'barangay'       => $this->barangay,
            'municipality'   => $this->municipality,
            'province'       => $this->province,
            'landline_no'    => $this->landline_no ?: null,
            'contact_no'     => $this->mobile_no ?: null,

            // Education
            'educ_attainment'=> $this->educational_attainment ?: null,

            // reviewed_by/on left null for now
        ]);

        // 2) form_occupation
        FormOccupation::create([
            'applicant_id'        => $personal->applicant_id,
            'occupation'          => $this->occupation ?: ($this->occupation_other ?: null),
            'employment_status'   => $this->employment_status ?: null,
            'employment_category' => $this->employment_category ?: null,
            'employment_type'     => $this->employment_type ?: null,
            'four_pmem'           => $this->four_ps_member ?: null,
        ]);

        // 3) form_oi (organization info)
        FormOi::create([
            'applicant_id'     => $personal->applicant_id,
            'oi_affiliated'    => $this->org_affiliated ?: null,
            'oi_contactperson' => $this->org_contact_person ?: null,
            'oi_telno'         => $this->org_contact_no ?: null,
            'oi_house_no'      => $this->org_house_no ?: null,
            'oi_street'        => $this->org_street ?: null,
            'oi_brgy'          => $this->org_brgy ?: null,
            'oi_municipality'  => $this->org_municipality ?: null,
            'oi_province'      => $this->org_province ?: null,
        ]);

        // 4) form_refnos
        FormRefnos::create([
            'applicant_id'    => $personal->applicant_id,
            'refno_sss'       => $this->id_sss ?: null,
            'refno_gsis'      => $this->id_gsis ?: null,
            'refno_pagibig'   => $this->id_pagibig ?: null,
            'refno_philhealth'=> $this->id_philhealth ?: null,
            'refno_others'    => $this->id_others ?: null,
        ]);

        // 5) form_guardian
        FormGuardian::create([
            'applicant_id'          => $personal->applicant_id,
            'mother_lname'          => $this->mother_last ?: null,
            'mother_fname'          => $this->mother_first ?: null,
            'mother_mname'          => $this->mother_middle ?: null,
            'mother_contact'        => $this->mother_contact ?: null,
            'father_lname'          => $this->father_last ?: null,
            'father_fname'          => $this->father_first ?: null,
            'father_mname'          => $this->father_middle ?: null,
            'father_contact'        => $this->father_contact ?: null,
            'spouse_guardian_lname' => $this->spouse_last ?: null,
            'spouse_guardian_fname' => $this->spouse_first ?: null,
            'spouse_guardian_mname' => $this->spouse_middle ?: null,
            'spouse_guardian_contact' => $this->spouse_contact ?: null,
            'physician_name'        => $this->physician_name ?: null,
        ]);

        // 6) Save uploaded files
        if (!empty(array_filter($this->files))) {
            $fileRecord = FormFile::create([
                'applicant_id' => $personal->applicant_id,
                'status' => 'incomplete'
            ]);

            $fileMetadata = [];
            $storagePath = 'form-documents/' . $personal->applicant_id;

            foreach ($this->files as $fileType => $file) {
                if ($file) {
                    $fileName = $fileType . '_' . time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($storagePath, $fileName, 'public');
                    
                    $fileRecord->$fileType = $filePath;
                    
                    $fileMetadata[$fileType] = [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }

            $fileRecord->file_metadata = $fileMetadata;
            
            if ($fileRecord->isComplete()) {
                $fileRecord->status = 'pending';
                $fileRecord->submitted_at = now();
            }

            $fileRecord->save();
        }

        // Optional: flash a success message and reset to step 1
        session()->flash('status', 'Application submitted successfully.');
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
