<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FormPersonal;
use App\Models\FormOccupation;
use App\Models\FormOi;
use App\Models\FormRefnos;
use App\Models\FormGuardian;
use Carbon\Carbon;

class StaffEncodeForm extends Component
{
    public int $step = 1;

    // ---- Account selection (important) ----
    public string $accountLookup = '';          // email or username
    public ?int $selectedAccountId = null;      // target user's account_id
    public ?string $accountError = null;
    public bool $encodingLocked = false;        // if user already has Finalized ID
    public ?string $encodingLockMessage = null;

    // ---- Snapshot of user (read-only summary at top) ----
    public ?string $summary_name = null;
    public ?string $summary_email = null;
    public ?string $summary_contact = null;
    public ?string $summary_address = null;
    public ?string $summary_pwd_number = null;  // if they already have one

    // PWD number reuse (for latest Rejected app)
    public ?string $reused_pwd_number = null;

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

    // Disability info
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

    public string $physician_last = '';
    public string $physician_first = '';
    public string $physician_middle = '';
    public string $physician_contact = '';

    protected $listeners = [
        'encode-account-verified' => 'onEncodeAccountVerified',
    ];

    public ?string $statusMessage = null;

    public function onEncodeAccountVerified($payload = null): void
    {
        $email = $payload['email'] ?? null;

        $this->statusMessage = $email
            ? "Account {$email} has been verified. You may now select this email in the Encode Form tab."
            : 'Account has been verified. You may now select this email in the Encode Form tab.';
    }

    // ------------------ HELPERS ------------------

    protected function normalizeUppercase(): void
    {
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
            'physician_last',
            'physician_first',
            'physician_middle',
            'physician_contact',
        ];

        foreach ($fields as $field) {
            if ($this->$field !== null && $this->$field !== '') {
                $this->$field = mb_strtoupper($this->$field, 'UTF-8');
            }
        }

        if ($this->municipality !== '') {
            $this->municipality = mb_strtoupper($this->municipality, 'UTF-8');
        }

        if ($this->province !== '') {
            $this->province = mb_strtoupper($this->province, 'UTF-8');
        }
    }

    /**
     * Generate a unique PWD number in the format 00-0000-000-0000000
     * (same generator as UserForm, but used here for encoded applications).
     */
    private function generateUniquePwdNumber(): string
    {
        do {
            $part1 = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $part2 = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $part3 = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $part4 = str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT);

            $pwdNumber = "{$part1}-{$part2}-{$part3}-{$part4}";
        } while (FormPersonal::where('pwd_number', $pwdNumber)->exists());

        return $pwdNumber;
    }

    public function mount()
    {
        // Nothing on mount; staff must choose an account first.
    }

    // Staff clicks "Load Account"
    public function loadAccount(): void
    {
        $this->accountError = null;
        $this->encodingLocked = false;
        $this->encodingLockMessage = null;
        $this->summary_pwd_number = null;
        $this->reused_pwd_number = null;   // reset reuse state

        $lookup = trim($this->accountLookup);

        if ($lookup === '') {
            $this->accountError = 'Please enter an email or username.';
            return;
        }

        /** @var User|null $account */
        $account = User::where('email', $lookup)
            ->orWhere('username', $lookup)
            ->first();

        if (!$account) {
            $this->accountError = 'No account found with that email or username.';
            return;
        }

        $this->selectedAccountId = $account->id;

        $profile = $account->profile; // may be null

        // Basic summary
        $nameParts = [];
        if ($profile && $profile->fname) $nameParts[] = $profile->fname;
        if ($profile && $profile->mname) $nameParts[] = $profile->mname;
        if ($profile && $profile->lname) $nameParts[] = $profile->lname;

        $this->summary_name = $nameParts ? implode(' ', $nameParts) : ($account->name ?? $account->username ?? null);
        $this->summary_email = $account->email ?? null;
        $this->summary_contact = $profile->contact_no ?? null;

        if ($profile) {
            $addrParts = array_filter([
                $profile->house_no ?? null,
                $profile->street ?? null,
                $profile->barangay ?? null,
                $profile->municipality ?? null,
                $profile->province ?? null,
            ]);
            $this->summary_address = $addrParts ? implode(', ', $addrParts) : null;
        } else {
            $this->summary_address = null;
        }

        // 🔒 Check latest ID application for this account
        $latestApp = FormPersonal::where('account_id', $account->id)
            ->orderByDesc('submitted_at')
            ->orderByDesc('submitted_at')
            ->first();

        if ($latestApp) {
            // Finalized = permanently locked for encoding
            if ($latestApp->status === 'Finalized') {
                $this->encodingLocked = true;

                $issueDate = $latestApp->date_issued
                    ?? $latestApp->submitted_at
                    ?? $latestApp->submitted_at;

                $dateLabel = $issueDate
                    ? Carbon::parse($issueDate)->format('M d, Y')
                    : 'N/A';

                $this->encodingLockMessage =
                    'This account already has a Finalized PWD ID (issued on ' . $dateLabel .
                    '). You cannot encode another ID application for this user.';

                $this->summary_pwd_number = $latestApp->pwd_number;
            }
            // Any status other than "Rejected" = user already has an application in process
            elseif ($latestApp->status !== 'Rejected') {
                $this->encodingLocked = true;

                $submittedDate = $latestApp->submitted_at ?? $latestApp->submitted_at;
                $dateLabel = $submittedDate
                    ? Carbon::parse($submittedDate)->format('M d, Y')
                    : 'N/A';

                $this->encodingLockMessage =
                    'This account already has a ' . $latestApp->status .
                    ' PWD ID application (submitted on ' . $dateLabel .
                    '). You cannot encode another ID application for this user until it is rejected.';

                $this->summary_pwd_number = $latestApp->pwd_number;
            } else {
                // ✅ Rejected: allow encoding and REUSE the previous PWD number
                $this->reused_pwd_number  = $latestApp->pwd_number;
                $this->summary_pwd_number = $latestApp->pwd_number;
            }
        }

        // If locked, skip prefill
        if ($this->encodingLocked) {
            $this->step = 1;
            return;
        }

        // Prefill fields from profile where possible, but keep editable
        if ($profile) {
            $this->first_name   = $profile->fname ?? '';
            $this->middle_name  = $profile->mname ?? null;
            $this->last_name    = $profile->lname ?? '';
            $this->gender       = strtoupper($profile->sex ?? '');
            $this->mobile_no    = $profile->contact_no ?? '';
            $this->date_of_birth = $profile->birthdate ?? null;
            $this->age          = $profile->age ?? '';
            $this->civil_status = $profile->civil_status ?? '';

            $this->house_no     = $profile->house_no ?? '';
            $this->street       = $profile->street ?? '';
            $this->barangay     = $profile->barangay ?? '';
            $this->municipality = $profile->municipality ?? 'CALAPAN CITY';
            $this->province     = $profile->province ?? 'ORIENTAL MINDORO';
        } else {
            // sensible defaults
            $this->municipality = 'CALAPAN CITY';
            $this->province     = 'ORIENTAL MINDORO';
        }

        $this->email = $account->email ?? '';

        // Reset step to 1 whenever you load a new account
        $this->step = 1;
    }

    // ---------- Step navigation (same idea as UserForm) ----------

    public function nextStep(): void
    {
        if (!$this->selectedAccountId) {
            $this->accountError = 'Please load an account first.';
            return;
        }

        if ($this->encodingLocked) {
            return;
        }

        if ($this->step === 1) {
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

            if ($this->disability_cause === 'Others, Specify'
                && trim((string) $this->disability_cause_other) === ''
            ) {
                $this->addError('disability_cause_other', 'Please specify cause of disability.');
                return;
            }
        } elseif ($this->step === 2) {
            $rules = [
                'educational_attainment' => 'required|string|max:100',
                'employment_status'      => 'required|string|max:100',
                'four_ps_member'         => 'required|string|max:5',
            ];

            if ($this->employment_status !== 'Unemployed') {
                $rules['employment_type']     = 'required|string|max:100';
                $rules['employment_category'] = 'required|string|max:100';
                $rules['occupation']          = 'required|string|max:150';
            }

            $this->validate($rules);

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

    // ---------- Small reactive helpers ----------

    public function updatedDateOfBirth($value)
    {
        if ($value) {
            try {
                $this->age = Carbon::parse($value)->age;
            } catch (\Exception $e) {
                $this->age = '';
            }
        } else {
            $this->age = '';
        }
    }

    public function updatedEmploymentStatus($value): void
    {
        if ($value === 'Unemployed') {
            $this->employment_type    = '';
            $this->employment_category = '';
            $this->occupation          = '';
            $this->occupation_other    = '';
        }
    }

    // ---------- Submit (similar to UserForm, NO FILES, ENCODED TYPE) ----------

    public function submit(): void
    {
        if (!$this->selectedAccountId) {
            $this->accountError = 'Please load an account first.';
            return;
        }

        if ($this->encodingLocked) {
            return;
        }

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

            // 🔹 disability (to mirror UserForm)
            'disability_type'  => 'required|string',
            'disability_cause' => 'required|string',

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

            // 🔹 Step 3 required (to mirror UserForm)
            'spouse_last'       => 'required|string|max:100',
            'spouse_first'      => 'required|string|max:100',
            'spouse_contact'    => 'required|string|max:50',
            'physician_last'     => 'required|string|max:100',
            'physician_first'    => 'required|string|max:100',
            'physician_middle'   => 'nullable|string|max:40',
            'physician_contact'  => 'required|string|max:50',
        ]);

        // At least one ID reference
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

        /** @var User|null $account */
        $account = User::find($this->selectedAccountId);

        if (!$account) {
            $this->accountError = 'Target account not found. Please reload the account.';
            return;
        }

        // Safety: check again for Finalized
        $existingFinal = FormPersonal::where('account_id', $account->id)
            ->where('status', 'Finalized')
            ->whereNotNull('date_issued')
            ->exists();

        if ($existingFinal) {
            $this->encodingLocked = true;
            $this->encodingLockMessage = 'This account already has a Finalized PWD ID. You cannot encode another application.';
            return;
        }

        // Safety: check again for any non-rejected latest application
        $latestApp = FormPersonal::where('account_id', $account->id)
            ->orderByDesc('submitted_at')
            ->orderByDesc('submitted_at')
            ->first();

        if ($latestApp && $latestApp->status !== 'Rejected') {
            $this->encodingLocked = true;

            if ($latestApp->status === 'Finalized') {
                $issueDate = $latestApp->date_issued
                    ?? $latestApp->submitted_at
                    ?? $latestApp->submitted_at;

                $dateLabel = $issueDate
                    ? Carbon::parse($issueDate)->format('M d, Y')
                    : 'N/A';

                $this->encodingLockMessage =
                    'This account already has a Finalized PWD ID (issued on ' . $dateLabel .
                    '). You cannot encode another ID application for this user.';
            } else {
                $submittedDate = $latestApp->submitted_at ?? $latestApp->submitted_at;
                $dateLabel = $submittedDate
                    ? Carbon::parse($submittedDate)->format('M d, Y')
                    : 'N/A';

                $this->encodingLockMessage =
                    'This account already has a ' . $latestApp->status .
                    ' PWD ID application (submitted on ' . $dateLabel .
                    '). You cannot encode another ID application for this user until it is rejected.';
            }

            $this->summary_pwd_number = $latestApp->pwd_number;
            $this->dispatch('scroll-to-top');
            return;
        }

        // ✅ Generate or reuse PWD number (new format: 00-0000-000-0000000)
        if ($this->reused_pwd_number) {
            // Reapply after Rejected → reuse old PWD number as-is
            $pwdNumber = $this->reused_pwd_number;
        } else {
            // First-time encoded application → generate new formatted PWD number
            $pwdNumber = $this->generateUniquePwdNumber();
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

        // Uppercase all the things (same as UserForm)
        $this->normalizeUppercase();

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

        $disabilityType  = $disabilityType  ? mb_strtoupper($disabilityType, 'UTF-8')  : null;
        $disabilityCause = $disabilityCause ? mb_strtoupper($disabilityCause, 'UTF-8') : null;
        $finalOccupation = $finalOccupation ? mb_strtoupper($finalOccupation, 'UTF-8') : null;

        // ---------------- DB Writes ----------------

        // 1) form_personal
        $personal = FormPersonal::create([
            'account_id'     => $account->id,
            'email'          => $this->email ?: ($account->email ?? ''),

            'status'         => 'Pending',
            'applicant_type' => 'Encoded Application',   // 👈 IMPORTANT FLAG
            'pwd_number'     => $pwdNumber,
            'submitted_at'   => now(),

            'fname'          => $this->first_name,
            'mname'          => $this->middle_name ?: null,
            'lname'          => $this->last_name,
            'suffix'         => $this->suffix ?: null,
            'birthdate'      => $this->date_of_birth,
            'age'            => $this->age ?: null,
            'sex'            => $this->gender,
            'blood_type'     => $this->blood_type ?: null,
            'civil_status'   => $this->civil_status,
            'disability_type'=> $disabilityType,
            'disability_cause'=> $disabilityCause,

            'house_no'       => $this->house_no ?: null,
            'street'         => $this->street ?: null,
            'barangay'       => $this->barangay,
            'municipality'   => $this->municipality,
            'province'       => $this->province,
            'landline_no'    => $this->landline_no ?: null,
            'contact_no'     => $this->mobile_no ?: null,

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

        // 3) form_oi
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
            'physician_lname'         => $this->physician_last ?: null,
            'physician_fname'         => $this->physician_first ?: null,
            'physician_mname'         => $this->physician_middle ?: null,
            'physician_contact'       => $this->physician_contact ?: null,
        ]);

        // Lock encoding for this account (PENDING state)
        $submittedDate = $personal->submitted_at ?? $personal->submitted_at;
        $dateLabel = $submittedDate
            ? Carbon::parse($submittedDate)->format('M d, Y')
            : 'N/A';

        $this->encodingLocked = true;
        $this->encodingLockMessage =
            'An encoded PWD ID application for this account is now Pending (submitted on ' .
            $dateLabel . '). You cannot encode another ID application for this user unless this one is rejected.';

        $this->summary_pwd_number = $personal->pwd_number;

        // Reset only the form fields (keep selected account + lock state)
        $this->step = 1;

        $this->reset([
            'blood_type',
            'civil_status',
            'date_of_birth',
            'age',
            'disability_type',
            'disability_cause',
            'disability_cause_other',
            'educational_attainment',
            'employment_type',
            'employment_status',
            'employment_category',
            'occupation',
            'occupation_other',
            'four_ps_member',
            'org_affiliated',
            'org_contact_person',
            'org_contact_no',
            'org_house_no',
            'org_street',
            'org_brgy',
            'org_municipality',
            'org_province',
            'id_sss',
            'id_gsis',
            'id_philhealth',
            'id_pagibig',
            'id_others',
            'id_others_type',
            'id_others_number',
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
            'physician_last',
            'physician_first',
            'physician_middle',
            'physician_contact',
        ]);

        session()->flash('success', 'Encoded ID application submitted successfully for this user.');

        $this->dispatch('scroll-to-top');
    }

    public function render()
    {
        return view('livewire.staff-encode-form');
    }
}
