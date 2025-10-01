<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FormFile extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'applicant_id',
        'id_picture',
        'psa',
        'cert_of_disability',
        'med_cert',
        'endorsement_letter',
        'file_metadata',
        'status',
        'admin_notes',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'file_metadata' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // File type mapping
    public static $fileTypes = [
        'id_picture' => '1x1 ID Picture',
        'psa' => 'PSA Birth Certificate',
        'cert_of_disability' => 'Certificate of Disability',
        'med_cert' => 'Medical Certificate',
        'endorsement_letter' => 'Endorsement Letter',
    ];

    // ✅ FIX: Specify the foreign key since you're using applicant_id
    public function formPersonal()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id');
    }

    // ✅ FIX: Get user through form_personal relationship since you don't have user_id
    public function user()
    {
        return $this->hasOneThrough(
            User::class,           // Final model
            FormPersonal::class,   // Intermediate model  
            'applicant_id',        // Foreign key on FormPersonal table
            'id',                  // Foreign key on User table
            'applicant_id',        // Local key on FormFile table
            'account_id'           // Local key on FormPersonal table
        );
    }

    // ✅ OPTIONAL: Add reviewer relationship if needed
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isComplete()
    {
        return $this->id_picture && $this->psa && $this->cert_of_disability && $this->med_cert && $this->endorsement_letter;
    }

    public function getCompletionPercentage()
    {
        $total = 5;
        $uploaded = 0;
        if ($this->id_picture) $uploaded++;
        if ($this->psa) $uploaded++;
        if ($this->cert_of_disability) $uploaded++;
        if ($this->med_cert) $uploaded++;
        if ($this->endorsement_letter) $uploaded++;
        
        return round(($uploaded / $total) * 100);
    }

    // ✅ USEFUL: Get file URLs for display
    public function getIdPictureUrlAttribute()
    {
        return $this->id_picture ? Storage::url($this->id_picture) : null;
    }

    public function getPsaUrlAttribute()
    {
        return $this->psa ? Storage::url($this->psa) : null;
    }

    public function getCertOfDisabilityUrlAttribute()
    {
        return $this->cert_of_disability ? Storage::url($this->cert_of_disability) : null;
    }

    public function getMedCertUrlAttribute()
    {
        return $this->med_cert ? Storage::url($this->med_cert) : null;
    }

    public function getEndorsementLetterUrlAttribute()
    {
        return $this->endorsement_letter ? Storage::url($this->endorsement_letter) : null;
    }

    // ✅ USEFUL: Get all uploaded files info
    public function getUploadedFiles()
    {
        $files = [];
        foreach (self::$fileTypes as $key => $label) {
            if ($this->$key) {
                $files[$key] = [
                    'label' => $label,
                    'path' => $this->$key,
                    'url' => Storage::url($this->$key),
                    'metadata' => $this->file_metadata[$key] ?? null,
                ];
            }
        }
        return $files;
    }

    // ✅ USEFUL: Get missing files
    public function getMissingFiles()
    {
        $missing = [];
        foreach (self::$fileTypes as $key => $label) {
            if (!$this->$key) {
                $missing[$key] = $label;
            }
        }
        return $missing;
    }

    // ✅ CLEANUP: Delete files from storage when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($file) {
            foreach (self::$fileTypes as $key => $label) {
                if ($file->$key && Storage::exists($file->$key)) {
                    Storage::delete($file->$key);
                }
            }
        });
    }
}