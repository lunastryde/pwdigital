<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\AccountsProfile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminAccounts extends Component
{
    // Form fields
    public $email;
    public $username;
    public $password;
    public $password_confirmation;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $contact_no;
    public $sex;
    public $identifier = 3; // Default to user
    public $role = 'user'; // Will auto-update based on identifier

    // UI state
    public $showCreateForm = false;

    protected function rules()
    {
        return [
            'email' => ['required', 'email', Rule::unique('accounts_master', 'email')],
            'username' => ['required', 'min:3', Rule::unique('accounts_master', 'username')],
            'password' => ['required', 'min:6', 'max:20'],
            'password_confirmation' => ['required', 'same:password'],
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'contact_no' => ['required', 'string', 'max:15'],
            'sex' => ['required', 'in:Male,Female'],
            'identifier' => ['required', 'in:1,2,3'],
        ];
    }

    protected $messages = [
        'password_confirmation.same' => 'Passwords do not match.',
        'identifier.required' => 'Please select an account type.',
    ];

    public function updatedIdentifier($value)
    {
        // Auto-update role based on identifier
        $roleMap = [
            1 => 'admin',
            2 => 'staff',
            3 => 'user'
        ];
        $this->role = $roleMap[$value] ?? 'user';
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
            
            $this->reset(['email', 'username', 'password', 'password_confirmation', 'first_name', 'middle_name', 'last_name', 'contact_no', 'sex', 'identifier']);
            $this->identifier = 3; // Reset to default
            $this->role = 'user';
            $this->showCreateForm = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create account. Please try again.');
            \Log::error('Account creation error: ' . $e->getMessage());
        }
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->reset();
            $this->identifier = 3;
            $this->role = 'user';
        }
    }

    public function render()
    {
        $accounts = User::with('profile')->orderByDesc('created_at')->get();
        
        return view('livewire.admin-accounts', [
            'accounts' => $accounts
        ]);
    }
}