<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPersonal;
use App\Models\FormOccupation;
use App\Models\FormRefnos;
use App\Models\FormGuardian;
use App\Models\FormOI;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormController extends Controller
{
    public function storeInformation(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            // Personal
            'fname' => 'required|string|max:100',
            'mname' => 'nullable|string|max:100',
            'lname' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'birthdate' => 'required|date',
            'sex' => 'required|string',
            'civil_status' => 'required|string',
            'disability_type' => 'required|string',
            'house_no' => 'nullable|string|max:50',
            'street' => 'nullable|string|max:150',
            'barangay' => 'required|string|max:100',
            'municipality' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'contact_no' => 'nullable|string|max:20',

            // Occupation
            'occupation' => 'nullable|string|max:150',
            'employment_status' => 'nullable|string|max:100',
            'employment_category' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string|max:100',

            // Org Info
            'oi_affiliated' => 'nullable|string|max:150',
            'oi_contactperson' => 'nullable|string|max:150',
            'oi_telno' => 'nullable|string|max:20',

            // Ref Nos
            'refno_sss' => 'nullable|string|max:20',
            'refno_gsis' => 'nullable|string|max:20',
            'refno_pagibig' => 'nullable|string|max:20',
            'refno_philhealth' => 'nullable|string|max:20',

            // Guardian
            'mother_fname' => 'nullable|string|max:100',
            'mother_mname' => 'nullable|string|max:100',
            'mother_lname' => 'nullable|string|max:100',
            'father_fname' => 'nullable|string|max:100',
            'father_mname' => 'nullable|string|max:100',
            'father_lname' => 'nullable|string|max:100',
            'spouse_guardian_fname' => 'nullable|string|max:100',
            'spouse_guardian_mname' => 'nullable|string|max:100',
            'spouse_guardian_lname' => 'nullable|string|max:100',
            'physician_name' => 'nullable|string|max:150',
        ]);

        // 2. Get the logged-in account (from accounts_master)
        $account = Auth::user();

        // 3. Insert into form_personal
        $personal = FormPersonal::create([
            'applicant_id'   => $account->id,
            'username'       => $account->username,
            'email'          => $account->email,
            'applicant_type' => 'ID Application',
            'date_applied'   => now(),
            'fname'          => $validated['fname'],
            'mname'          => $validated['mname'] ?? null,
            'lname'          => $validated['lname'],
            'suffix'         => $validated['suffix'] ?? null,
            'birthdate'      => $validated['birthdate'],
            'sex'            => $validated['sex'],
            'civil_status'   => $validated['civil_status'],
            'disability_type'=> $validated['disability_type'],
            'house_no'       => $validated['house_no'] ?? null,
            'street'         => $validated['street'] ?? null,
            'barangay'       => $validated['barangay'],
            'municipality'   => $validated['municipality'],
            'province'       => $validated['province'],
            'contact_no'     => $validated['contact_no'] ?? null,
        ]);

        // 4. Insert into related tables
        FormOccupation::create([
            'applicant_id'       => $personal->applicant_id,
            'occupation'         => $validated['occupation'] ?? null,
            'employment_status'  => $validated['employment_status'] ?? null,
            'employment_category'=> $validated['employment_category'] ?? null,
            'employment_type'    => $validated['employment_type'] ?? null,
        ]);

        FormOi::create([
            'applicant_id'       => $personal->applicant_id,
            'oi_affiliated'      => $validated['oi_affiliated'] ?? null,
            'oi_contactperson'   => $validated['oi_contactperson'] ?? null,
            'oi_telno'           => $validated['oi_telno'] ?? null,
        ]);

        FormRefnos::create([
            'applicant_id'  => $personal->applicant_id,
            'refno_sss'     => $validated['refno_sss'] ?? null,
            'refno_gsis'    => $validated['refno_gsis'] ?? null,
            'refno_pagibig' => $validated['refno_pagibig'] ?? null,
            'refno_philhealth' => $validated['refno_philhealth'] ?? null,
        ]);

        FormGuardian::create([
            'applicant_id'          => $personal->applicant_id,
            'mother_fname'          => $validated['mother_fname'] ?? null,
            'mother_mname'          => $validated['mother_mname'] ?? null,
            'mother_lname'          => $validated['mother_lname'] ?? null,
            'father_fname'          => $validated['father_fname'] ?? null,
            'father_mname'          => $validated['father_mname'] ?? null,
            'father_lname'          => $validated['father_lname'] ?? null,
            'spouse_guardian_fname' => $validated['spouse_guardian_fname'] ?? null,
            'spouse_guardian_mname' => $validated['spouse_guardian_mname'] ?? null,
            'spouse_guardian_lname' => $validated['spouse_guardian_lname'] ?? null,
            'physician_name'        => $validated['physician_name'] ?? null,
        ]);

        // 5. Return response
        return response()->json([
            'message' => 'Application submitted successfully',
            'data'    => $personal->load(['occupation', 'oi', 'refnos', 'guardian']),
        ], 201);
    }
}
