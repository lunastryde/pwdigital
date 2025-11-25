<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserEdit extends Component
{
    use WithFileUploads;

    public $fname, $mname, $lname;
    public $contact_no, $civil_status;

    public $profile_picture;

    public $password = '';
    public $password_confirmation = '';

    public bool $showRemovePictureConfirm = false;

    public $role;

    public function mount()
    {
        $profile = Auth::user()->profile;
        $this->role = Auth::user()->role; // user/staff/admin

        // Fill from accounts_profile
        $this->fname         = $profile->fname;
        $this->mname         = $profile->mname;
        $this->lname         = $profile->lname;
        $this->contact_no    = $profile->contact_no;
        $this->civil_status  = $profile->civil_status;
    }

    public function save()
    {
        $this->validate([
            'fname'       => 'required|string|max:100',
            'mname'       => 'nullable|string|max:100',
            'lname'       => 'required|string|max:100',
            'contact_no'  => 'required|string|max:50',
            'civil_status'=> ($this->role === 'user' ? 'nullable' : 'nullable'),
        ]);

        Auth::user()->profile->update([
            'fname'        => $this->fname,
            'mname'        => $this->mname,
            'lname'        => $this->lname,
            'contact_no'   => $this->contact_no,
            'civil_status' => $this->civil_status,
        ]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:6|max:20|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('success', 'Password updated successfully!');
    }

    public function uploadProfilePicture()
    {
        if (!$this->profile_picture) {
            $this->addError('profile_picture', 'Please select a file first.');
            return;
        }

        $this->validate([
            'profile_picture' => 'image|max:2048',
        ]);

        $path = $this->profile_picture->store('profile_pictures', 'public');

        Auth::user()->profile->update([
            'profile_picture' => $path,
        ]);

        session()->flash('success', 'Profile picture updated!');
    }

    public function removeProfilePicture()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Delete stored file
        if ($profile->profile_picture && \Storage::disk('public')->exists($profile->profile_picture)) {
            \Storage::disk('public')->delete($profile->profile_picture);
        }

        // Remove from DB
        $profile->update([
            'profile_picture' => null,
        ]);

        // Reset temporary file
        $this->profile_picture = null;

        $this->showRemovePictureConfirm = false;

        session()->flash('success', 'Profile picture removed successfully.');
    }


    public function confirmRemovePicture()
    {
        $this->showRemovePictureConfirm = true;
    }

    public function render()
    {
        return view('livewire.user-edit');
    }
}
