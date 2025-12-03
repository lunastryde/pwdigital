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

    public int $step = 1;

    public ?int $reapply_from_applicant_id = null; // last Cancelled/Rejected app
    public ?string $reused_pwd_number = null;      // PWD number to reuse

    // Step 1: Personal Information + Disability
    public string $first_name = '';
    public ?string $middle_name = null;
    public string $last_name = '';
    public string $suffix = '';
    public ?string $date_of_birth = null;
    public string $blood_type = '';
    public ?string $age = '';

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
    public string $disability_type = '';
    public string $disability_cause = '';
    public ?string $disability_cause_other = '';

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
    public string $id_pagibig = '';
    public string $id_others = '';
    public string $id_others_type = '';
    public string $id_others_number = '';


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

    protected function normalizeUppercase(): void
    {
        // Plain text fields we want stored as UPPERCASE
        $fields = [
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'house_no',
            'street',
            'barangay',
            'org_affiliated',
            'org_contact_person',
            'org_contact_no',
            'org_house_no',
            'org_street',
            'org_brgy',
            'org_municipality',
            'org_province',
            'father_last',
            'father_first',
            'father_middle',
            'father_contact',
            'mother_last',
            'mother_first',
            'mother_middle',
            'mother_contact',
            'spouse_last',
            'spouse_first',
            'spouse_middle',
            'spouse_contact',
            'physician_name',
        ];

        foreach ($fields as $field) {
            if ($this->$field !== null && $this->$field !== '') {
                $this->$field = mb_strtoupper($this->$field, 'UTF-8');
            }
        }

        // Just to be extra sure these stay uppercase too
        if ($this->municipality !== '') {
            $this->municipality = mb_strtoupper($this->municipality, 'UTF-8');
        }

        if ($this->province !== '') {
            $this->province = mb_strtoupper($this->province, 'UTF-8');
        }
    }

    public function mount()
    {
        $account = Auth::user();

        if (!$account) {
            return redirect()->route('login');
        }

        $accountId = $account->id;

        // Get latest application for this account (any status)
        $latest = FormPersonal::where('account_id', $accountId)
            ->orderByDesc('submitted_at')
            ->orderByDesc('submitted_at')
            ->first();

        // ---------- BLOCKING LOGIC ----------
        if ($latest) {
            // 1. Finalized -> permanently blocked
            if ($latest->status === 'Finalized') {
                session()->flash(
                    'error',
                    'You already have an active PWD ID. Please request renewal or lost ID instead.'
                );
                return redirect()->route('home', ['tab' => 'applications']);
            }

            // 2. Pending -> block duplicate application
            if ($latest->status === 'Pending') {
                session()->flash(
                    'error',
                    'You already have a pending PWD ID application. You may only submit a new application if your previous one is cancelled or rejected.'
                );
                return redirect()->route('home', ['tab' => 'applications']);
            }

            // 3. Rejected / Cancelled -> allow re-apply, reuse PWD + prefill
            if (in_array($latest->status, ['Rejected', 'Cancelled'], true)) {
                $this->reapply_from_applicant_id = $latest->applicant_id;
                $this->reused_pwd_number = $latest->pwd_number;

                // -------- Prefill from form_personal --------
                $this->first_name     = $latest->fname ?? '';
                $this->middle_name    = $latest->mname;
                $this->last_name      = $latest->lname ?? '';
                $this->suffix         = $latest->suffix ?? '';
                $this->date_of_birth  = $latest->birthdate;
                $this->age            = $latest->age;
                $this->gender         = $latest->sex ?? '';
                $this->blood_type     = $latest->blood_type ?? '';

                // Civil status -> map DB uppercase back to radio labels
                $this->civil_status = '';
                if ($latest->civil_status) {
                    $civilOptions = ['Single','Separated','Cohabitation','Married','Widowed'];
                    $mappedCivil = null;
                    foreach ($civilOptions as $opt) {
                        if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($latest->civil_status, 'UTF-8')) {
                            $mappedCivil = $opt;
                            break;
                        }
                    }
                    $this->civil_status = $mappedCivil ?? $latest->civil_status;
                }

                $this->house_no      = $latest->house_no ?? '';
                $this->street        = $latest->street ?? '';
                $this->barangay      = $latest->barangay ?? '';
                $this->municipality  = $latest->municipality ?? 'CALAPAN CITY';
                $this->province      = $latest->province ?? 'ORIENTAL MINDORO';
                $this->landline_no   = $latest->landline_no ?? '';
                $this->mobile_no     = $latest->contact_no ?? '';
                $this->email         = $latest->email ?: ($account->email ?? '');

                // Disability type is stored uppercase, same as radio values
                $this->disability_type = $latest->disability_type ?? '';

                // Disability cause: handle "Others, Specify"
                $storedCause = $latest->disability_cause;
                $this->disability_cause = '';
                $this->disability_cause_other = '';

                if ($storedCause) {
                    $knownCauses = [
                        'CONGENITAL OR INBORN',
                        'AUTISM',
                        'ADHD',
                        'CEREBRAL PALSY',
                        'ACQUIRED',
                        'CHRONIC ILLNESS',
                        'INJURY',
                        'OTHERS, SPECIFY',
                    ];
                    $upperCause = mb_strtoupper($storedCause, 'UTF-8');

                    if (in_array($upperCause, $knownCauses, true) && $upperCause !== 'OTHERS, SPECIFY') {
                        $this->disability_cause = $upperCause;
                        $this->disability_cause_other = '';
                    } else {
                        $this->disability_cause = 'Others, Specify';
                        $this->disability_cause_other = $upperCause === 'OTHERS, SPECIFY'
                            ? ''
                            : $storedCause;
                    }
                }

                // Educational attainment (map back to label)
                $this->educational_attainment = '';
                if ($latest->educ_attainment) {
                    $educOptions = [
                        'None','Kindergarten','Elementary','Junior Highschool','Senior Highschool',
                        'College','Vocational / ALS','Post Graduate Program'
                    ];
                    $mappedEduc = null;
                    foreach ($educOptions as $opt) {
                        if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($latest->educ_attainment, 'UTF-8')) {
                            $mappedEduc = $opt;
                            break;
                        }
                    }
                    $this->educational_attainment = $mappedEduc ?? $latest->educ_attainment;
                }

                // -------- Prefill from form_occupation --------
                $occRow = FormOccupation::where('applicant_id', $latest->applicant_id)->first();
                if ($occRow) {
                    // Employment status
                    $this->employment_status = '';
                    if ($occRow->employment_status) {
                        $statusOptions = ['Employed','Unemployed','Self-employed'];
                        $mappedStatus = null;
                        foreach ($statusOptions as $opt) {
                            if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($occRow->employment_status, 'UTF-8')) {
                                $mappedStatus = $opt;
                                break;
                            }
                        }
                        $this->employment_status = $mappedStatus ?? $occRow->employment_status;
                    }

                    // Employment type
                    $this->employment_type = '';
                    if ($occRow->employment_type) {
                        $typeOptions = ['Permanent / Regular','Seasonal','Casual','Emergency'];
                        $mappedType = null;
                        foreach ($typeOptions as $opt) {
                            if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($occRow->employment_type, 'UTF-8')) {
                                $mappedType = $opt;
                                break;
                            }
                        }
                        $this->employment_type = $mappedType ?? $occRow->employment_type;
                    }

                    // Employment category
                    $this->employment_category = '';
                    if ($occRow->employment_category) {
                        $catOptions = ['Government','Private'];
                        $mappedCat = null;
                        foreach ($catOptions as $opt) {
                            if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($occRow->employment_category, 'UTF-8')) {
                                $mappedCat = $opt;
                                break;
                            }
                        }
                        $this->employment_category = $mappedCat ?? $occRow->employment_category;
                    }

                    // Occupation (handle “Others, Specify”)
                    $this->occupation = '';
                    $this->occupation_other = '';
                    if ($occRow->occupation) {
                        $occOptions = [
                            'Manager','Professionals','Technician & Associate Professionals','Clerical Support',
                            'Service & Sales Workers',
                            'Skilled Agricultural, Forestry and Fishery Workers',
                            'Armed Forces Occupation',
                            'Elementary Occupation',
                            'Crafts & Related Trade Workers',
                            'Others, Specify',
                        ];
                        $matchedLabel = null;
                        foreach ($occOptions as $opt) {
                            if (mb_strtoupper($opt, 'UTF-8') === mb_strtoupper($occRow->occupation, 'UTF-8')) {
                                $matchedLabel = $opt;
                                break;
                            }
                        }

                        if ($matchedLabel && $matchedLabel !== 'Others, Specify') {
                            $this->occupation = $matchedLabel;
                            $this->occupation_other = '';
                        } else {
                            $this->occupation = 'Others, Specify';
                            $this->occupation_other = $occRow->occupation;
                        }
                    }

                    $this->four_ps_member = $occRow->four_pmem ?? '';
                }

                // -------- Prefill from form_oi --------
                $orgRow = FormOi::where('applicant_id', $latest->applicant_id)->first();
                if ($orgRow) {
                    $this->org_affiliated      = $orgRow->oi_affiliated ?? '';
                    $this->org_contact_person  = $orgRow->oi_contactperson ?? '';
                    $this->org_contact_no      = $orgRow->oi_telno ?? '';
                    $this->org_house_no        = $orgRow->oi_house_no ?? '';
                    $this->org_street          = $orgRow->oi_street ?? '';
                    $this->org_brgy            = $orgRow->oi_brgy ?? '';
                    $this->org_municipality    = $orgRow->oi_municipality ?? '';
                    $this->org_province        = $orgRow->oi_province ?? '';
                }

                // -------- Prefill from form_refnos --------
                $refRow = FormRefnos::where('applicant_id', $latest->applicant_id)->first();
                if ($refRow) {
                    $this->id_sss        = $refRow->refno_sss ?? '';
                    $this->id_gsis       = $refRow->refno_gsis ?? '';
                    $this->id_pagibig    = $refRow->refno_pagibig ?? '';
                    $this->id_philhealth = $refRow->refno_philhealth ?? '';

                    $this->id_others_type = '';
                    $this->id_others_number = '';
                    if (!empty($refRow->refno_others)) {
                        if (strpos($refRow->refno_others, ' - ') !== false) {
                            [$type, $num] = explode(' - ', $refRow->refno_others, 2);
                            $this->id_others_type = $type;
                            $this->id_others_number = $num;
                        } else {
                            $this->id_others_number = $refRow->refno_others;
                        }
                    }
                }

                // -------- Prefill from form_guardian --------
                $guardRow = FormGuardian::where('applicant_id', $latest->applicant_id)->first();
                if ($guardRow) {
                    $this->mother_last     = $guardRow->mother_lname ?? '';
                    $this->mother_first    = $guardRow->mother_fname ?? '';
                    $this->mother_middle   = $guardRow->mother_mname ?? '';
                    $this->mother_contact  = $guardRow->mother_contact ?? '';

                    $this->father_last     = $guardRow->father_lname ?? '';
                    $this->father_first    = $guardRow->father_fname ?? '';
                    $this->father_middle   = $guardRow->father_mname ?? '';
                    $this->father_contact  = $guardRow->father_contact ?? '';

                    $this->spouse_last     = $guardRow->spouse_guardian_lname ?? '';
                    $this->spouse_first    = $guardRow->spouse_guardian_fname ?? '';
                    $this->spouse_middle   = $guardRow->spouse_guardian_mname ?? '';
                    $this->spouse_contact  = $guardRow->spouse_guardian_contact ?? '';

                    $this->physician_name  = $guardRow->physician_name ?? '';
                }

                // Files remain empty on re-apply (never prefilled)
                return;
            }
        }

        // ---------- Fallback: first-time application (original behaviour) ----------
        $profile = $account->profile;

        // Prefill readonly fields (from registration)
        $this->first_name    = $profile->fname ?? '';
        $this->middle_name   = $profile->mname;
        $this->last_name     = $profile->lname ?? '';
        $this->gender        = strtoupper($profile->sex ?? '');
        $this->mobile_no     = $profile->contact_no ?? '';
        $this->date_of_birth = $profile->birthdate;
        $this->age           = $profile->age;

        $this->municipality  = 'CALAPAN CITY';
        $this->province      = 'ORIENTAL MINDORO';
        $this->email         = $account->email ?? '';
    }


    public function nextStep(): void
    {
        if ($this->step === 1) {
            // Validate STEP 1 before going to STEP 2
            $this->validate([
                'first_name'       => 'required|string|max:100',
                'last_name'        => 'required|string|max:100',
                'date_of_birth'    => 'required|date',
                'gender'           => 'required|string',
                'civil_status'     => 'required|string',
                'blood_type'       => 'required|string',
                'house_no'         => 'required|string|max:100',
                'street'           => 'required|string|max:100',
                'barangay'         => 'required|string|max:100',
                'municipality'     => 'required|string|max:100',
                'province'         => 'required|string|max:100',
                'disability_type'  => 'required|string',
                'disability_cause' => 'required|string',
            ]);

            // Manual check for "Others, Specify" since it has a comma
            if ($this->disability_cause === 'Others, Specify'
                && trim((string) $this->disability_cause_other) === ''
            ) {
                $this->addError('disability_cause_other', 'Please specify cause of disability.');
                return;
            }
        } elseif ($this->step === 2) {
            // Validate STEP 2 before going to STEP 3
            $rules = [
                'educational_attainment' => 'required|string|max:100',
                'employment_status'      => 'required|string|max:100',
                'four_ps_member'         => 'required|string|max:5',
            ];

            // Only require these if not Unemployed
            if ($this->employment_status !== 'Unemployed') {
                $rules['employment_type']    = 'required|string|max:100';
                $rules['employment_category'] = 'required|string|max:100';
                $rules['occupation']          = 'required|string|max:150';
            }

            $this->validate($rules);

            // Same "at least one ID" rule but checked before leaving Step 2
            if (
                empty($this->id_sss) &&
                empty($this->id_gsis) &&
                empty($this->id_philhealth) &&
                empty($this->id_pagibig) &&
                (empty($this->id_others_type) || empty($this->id_others_number))
            ) {
                $this->addError('id_reference', 'Please provide at least one valid ID number.');
                return;
            }
        }

        if ($this->step < 3) {
            $this->step++;
        }

        $this->dispatch('scroll-to-top');
    }


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

    public function isFormComplete(): bool
    {
        if ($this->step !== 3) {
            return false;
        }

        // STEP 1: Required text-like fields
        $requiredStep1 = [
            $this->first_name,
            $this->last_name,
            $this->date_of_birth,
            $this->gender,
            $this->civil_status,
            $this->blood_type,
            $this->house_no,
            $this->street,
            $this->barangay,
            $this->municipality,
            $this->province,
            $this->disability_type,
            $this->disability_cause,
        ];

        foreach ($requiredStep1 as $value) {
            if (trim((string) $value) === '') {
                return false;
            }
        }

        if ($this->disability_cause === 'Others, Specify' &&
            trim((string) $this->disability_cause_other) === ''
        ) {
            return false;
        }

        if (trim($this->educational_attainment) === '' ||
            trim($this->employment_status) === '' ||
            trim($this->four_ps_member) === ''
        ) {
            return false;
        }

        if ($this->employment_status !== 'Unemployed') {
            if (
                trim($this->employment_type) === '' ||
                trim($this->employment_category) === '' ||
                trim($this->occupation) === ''
            ) {
                return false;
            }

            if ($this->occupation === 'Others, Specify' &&
                trim((string) $this->occupation_other) === ''
            ) {
                return false;
            }
        }

        $hasAnyId =
            !empty($this->id_sss) ||
            !empty($this->id_gsis) ||
            !empty($this->id_philhealth) ||
            !empty($this->id_pagibig) ||
            (!empty($this->id_others_type) && !empty($this->id_others_number));

        if (!$hasAnyId) {
            return false;
        }

        $requiredStep3 = [
            $this->spouse_last,
            $this->spouse_first,
            $this->spouse_contact,
            $this->physician_name,
        ];

        foreach ($requiredStep3 as $value) {
            if (trim((string) $value) === '') {
                return false;
            }
        }

        // FILES
        $requiredFiles = [
            'id_picture',
            'psa',
            'cert_of_disability',
            'med_cert',
            'endorsement_letter',
        ];

        foreach ($requiredFiles as $key) {
            if (empty($this->files[$key])) {
                return false;
            }
        }

        return true;
    }


    public function submit(): void
    {

        if (!$this->allFilesUploaded()) {
            if (empty($this->files['id_picture'])) {
                $this->addError('files.id_picture', '1x1 ID Picture is required.');
            }

            if (empty($this->files['psa'])) {
                $this->addError('files.psa', 'PSA Birth Certificate is required.');
            }

            if (empty($this->files['cert_of_disability'])) {
                $this->addError('files.cert_of_disability', 'Certificate of Disability is required.');
            }

            if (empty($this->files['med_cert'])) {
                $this->addError('files.med_cert', 'Medical Certificate is required.');
            }

            if (empty($this->files['endorsement_letter'])) {
                $this->addError('files.endorsement_letter', 'Endorsement Letter is required.');
            }

            $this->step = 3;
            return;
        }

        $this->validate($this->rules());

        // Basic validation for required fields present in the UI
        $this->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'date_of_birth'    => 'required|date',
            'gender'           => 'required|string',
            'civil_status'     => 'required|string',
            'blood_type'       => 'required|string',

            'house_no'         => 'required|string|max:100',
            'street'           => 'required|string|max:100',
            'barangay'         => 'required|string|max:100',
            'municipality'     => 'required|string|max:100',
            'province'         => 'required|string|max:100',

            'disability_type'  => 'required|string',
            'disability_cause' => 'required|string',

            // Step 2 (some conditionally required, DB still accepts null)
            'educational_attainment' => 'required|string|max:100',
            'employment_status'      => 'required|string|max:100',
            'employment_type'        => 'nullable|string|max:100',
            'employment_category'    => 'nullable|string|max:100',
            'occupation'             => 'nullable|string|max:150',
            'four_ps_member'         => 'required|string|max:5',

            'id_sss'            => 'nullable|numeric|digits:10',
            'id_philhealth'     => 'nullable|numeric|digits:12',
            'id_pagibig'        => 'nullable|numeric|digits:12',
            'id_gsis'           => 'nullable|numeric|digits_between:10,12',
            'id_others_number'  => 'nullable|string|max:20',

            // ✅ Step 3 required (father/mother still optional)
            'spouse_last'       => 'required|string|max:100',
            'spouse_first'      => 'required|string|max:100',
            'spouse_contact'    => 'required|string|max:50',
            'physician_name'    => 'required|string|max:150',
        ]);

        $account = Auth::user();

        if (!$account) {
            $this->addError('auth', 'You must be logged in to submit an application.');
            return;
        }

        // Generate or reuse 8-digit PWD number
        if ($this->reused_pwd_number) {
            // Reapply after Cancelled/Rejected → reuse old PWD number
            $pwdNumber = $this->reused_pwd_number;
        } else {
            // First-time application → generate new PWD number
            $pwdNumber = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        }

        // --- Require at least one ID reference ---
        if (
            empty($this->id_sss) &&
            empty($this->id_gsis) &&
            empty($this->id_philhealth) &&
            empty($this->id_pagibig) &&
            (empty($this->id_others_type) || empty($this->id_others_number))
        ) {
            $this->addError('id_reference', 'Please provide at least one valid ID number.');
            $this->step = 2;
            return;
        }

        // Compose disability strings
        $disabilityType = $this->disability_type ?: null;

        if ($this->disability_cause === 'Others, Specify') {
            $disabilityCause = $this->disability_cause_other ?: 'Others, Specify';
        } else {
            $disabilityCause = $this->disability_cause ?: null;
        }

        // Compose occupation field
        if ($this->occupation === 'Others, Specify') {
            $finalOccupation = $this->occupation_other ?: 'Others, Specify';
        } else {
            $finalOccupation = $this->occupation ?: null;
        }

        // Compose refno_others
        if (!empty($this->id_others_type) && !empty($this->id_others_number)) {
            $refOther = $this->id_others_type . ' - ' . $this->id_others_number;
        } else {
            $refOther = null;
        }

        // 🔹 1) Uppercase all plain text fields on the component
        $this->normalizeUppercase();

        // 🔹 2) Uppercase radio/select string fields (choices)
        if ($this->civil_status !== '') {
            $this->civil_status = mb_strtoupper($this->civil_status, 'UTF-8');
        }

        if ($this->employment_type !== '') {
            $this->employment_type = mb_strtoupper($this->employment_type, 'UTF-8');
        }

        if ($this->employment_status !== '') {
            $this->employment_status = mb_strtoupper($this->employment_status, 'UTF-8');
        }

        if ($this->employment_category !== '') {
            $this->employment_category = mb_strtoupper($this->employment_category, 'UTF-8');
        }

        if ($this->four_ps_member !== '') {
            $this->four_ps_member = mb_strtoupper($this->four_ps_member, 'UTF-8');
        }

        if ($this->educational_attainment !== '') {
            $this->educational_attainment = mb_strtoupper($this->educational_attainment, 'UTF-8');
        }

        // 🔹 3) Uppercase the final composed special fields
        $disabilityType  = $disabilityType  ? mb_strtoupper($disabilityType, 'UTF-8')  : null;
        $disabilityCause = $disabilityCause ? mb_strtoupper($disabilityCause, 'UTF-8') : null;
        $finalOccupation = $finalOccupation ? mb_strtoupper($finalOccupation, 'UTF-8') : null;

        // 1) form_personal
        $personal = FormPersonal::create([
            'account_id'     => $account->id,

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
            'blood_type'      => $this->blood_type ?: null,
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
        ]);

        // 2) form_occupation
        FormOccupation::create([
            'applicant_id'        => $personal->applicant_id,
            'occupation'          => $finalOccupation,
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
            'refno_others'    => $refOther,
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

        session()->flash('success', 'Your ID Application was submitted successfully!');
        $this->redirect(route('home', ['tab' => 'applications']));
    }

    public function closeErrorModal(): void
    {
        // Clear all validation errors so the modal disappears
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
