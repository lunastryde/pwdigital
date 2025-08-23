<?php

namespace App\Livewire;

use Livewire\Component;

class UserForm extends Component
{
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

    public string $mother_last = '';
    public string $mother_first = '';
    public string $mother_middle = '';

    public string $spouse_last = '';
    public string $spouse_first = '';
    public string $spouse_middle = '';
    public string $spouse_contact = '';
    

    /**
     * Go to next step (no validation yet; purely UI).
     */
    public function nextStep(): void
    {
        if ($this->step < 3) {
            $this->step++;
        }
    }

    /**
     * Go back to previous step.
     */
    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    /**
     * Placeholder submit handler. No backend/queries yet.
     */
    public function submit(): void
    {
        // Intentionally left blank for now (UI only)
        // You can add validation and persistence later.
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
