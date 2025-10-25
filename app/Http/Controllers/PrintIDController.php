<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPersonal;

class PrintIDController extends Controller
{
    public function print($id)
    {
        $form = FormPersonal::with('files')->where('applicant_id', $id)->firstOrFail();
        if ($form->status !== 'Finalized') {
            // Block printing if not finalized
            abort(403, 'ID not finalized yet.');
        }
        return view('id-print', compact('form'));
    }
}
