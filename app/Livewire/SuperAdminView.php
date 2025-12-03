<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SuperAdminView extends Component
{
    // Form inputs
    public $email, $username, $password, $password_confirmation;
    public $first_name, $middle_name, $last_name, $contact_no, $sex;

    // 1 = Admin, 2 = Staff
    public $identifier = 2; 
    public $role = 'staff';

    // UI
    public $showCreateForm = false;

    // Deactivation state
    public ?int $selectedUserId = null;
    public bool $showDeactivateConfirm = false;
    public bool $showReactivateConfirm = false;

    protected function rules()
    {
        return [
            'identifier' => ['required', 'in:1,2'], 

            'email' => ['required', 'email', Rule::unique('accounts_master', 'email')],
            'username' => ['required', 'min:3', Rule::unique('accounts_master', 'username')],

            'password' => ['required', 'min:6', 'max:20'],
            'password_confirmation' => ['required', 'same:password'],

            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'contact_no' => ['required', 'string', 'max:15'],
            'sex' => ['required', 'in:Male,Female'],
        ];
    }

    public function updatedIdentifier($value)
    {
        $map = [
            1 => 'admin',
            2 => 'staff',
        ];

        $this->role = $map[$value] ?? 'staff';
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;

        if (!$this->showCreateForm) {
            $this->resetForm();
        }
    }

    public function createAccount()
    {
        $this->validate();

        try {
            $user = User::create([
                'email' => $this->email,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'identifier' => $this->identifier,
                'role' => $this->role,
            ]);

            $user->profile()->create([
                'fname' => $this->first_name,
                'mname' => $this->middle_name,
                'lname' => $this->last_name,
                'contact_no' => $this->contact_no,
                'sex' => $this->sex,
            ]);

            session()->flash('success', ucfirst($this->role) . ' account created successfully!');

            $this->resetForm();
            $this->showCreateForm = false;

        } catch (\Throwable $e) {
            \Log::error("SuperAdmin create error: " . $e->getMessage());
            session()->flash('error', 'Failed to create account.');
        }
    }

    public function resetForm()
    {
        $this->reset([
            'email',
            'username',
            'password',
            'password_confirmation',
            'first_name',
            'middle_name',
            'last_name',
            'contact_no',
            'sex',
        ]);

        $this->identifier = 2; 
        $this->role = 'staff';
    }

    public function confirmDeactivate(int $userId)
    {
        $this->selectedUserId = $userId;
        $this->showDeactivateConfirm = true;
    }

    public function deactivateUser()
    {
        if (!$this->selectedUserId) return;

        $user = User::find($this->selectedUserId);

        if ($user) {
            $user->update(['is_active' => 0]);
        }

        $this->showDeactivateConfirm = false;
        $this->selectedUserId = null;

        session()->flash('success', 'Account has been deactivated.');
    }

    public function confirmReactivate($userId)
    {
        $this->selectedUserId = $userId;
        $this->showReactivateConfirm = true;
    }

    public function reactivateUser()
    {
        if (!$this->selectedUserId) return;

        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        try {
            $user->is_active = 1;
            $user->save();

            session()->flash('success', 'Account has been reactivated.');
        } catch (\Throwable $e) {
            \Log::error("Reactivate error: " . $e->getMessage());
            session()->flash('error', 'Failed to reactivate account.');
        }

        $this->showReactivateConfirm = false;
        $this->selectedUserId = null;
    }

    public function render()
    {
        // Only ADMIN + STAFF (exclude superadmin + user)
        $accounts = User::with('profile')
            ->whereIn('identifier', [1, 2])
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.super-admin-view', [
            'accounts' => $accounts,
        ]);
    }
}
