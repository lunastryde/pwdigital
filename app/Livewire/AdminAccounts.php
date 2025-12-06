<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Profile;
use Carbon\Carbon;
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
    public $birthdate;
    public $age;

    public $search = '';

    // UI state
    public $showCreateForm = false;

    // Deactivation state
    public ?int $selectedUserId = null;
    public bool $showDeactivateConfirm = false;
    public bool $showReactivateConfirm = false;

    /**
     * Custom messages (for password regex)
     */
    protected $messages = [
        'password.regex' => 'Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, and one number.',
    ];

    protected function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('accounts_master', 'email'),
            ],
            'username' => [
                'required',
                'min:3',
                Rule::unique('accounts_master', 'username'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                // at least one lowercase, one uppercase, one digit
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
            'password_confirmation' => [
                'required',
                'same:password',
            ],
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'contact_no' => ['required', 'string', 'max:15'],
            'sex' => ['required', 'in:Male,Female'],
            'birthdate' => ['required', 'date', 'before:today'],
        ];
    }

    public function updatedBirthdate()
    {
        if ($this->birthdate) {
            $this->age = Carbon::parse($this->birthdate)->age;
        }
    }

    public function createAccount()
    {
        $incomingData = $this->validate();

        // ---- UPPERCASE NORMALIZATION (names + sex) ----
        $firstName  = mb_strtoupper($incomingData['first_name'], 'UTF-8');
        $middleName = isset($incomingData['middle_name']) && $incomingData['middle_name'] !== ''
            ? mb_strtoupper($incomingData['middle_name'], 'UTF-8')
            : null;
        $lastName   = mb_strtoupper($incomingData['last_name'], 'UTF-8');
        $sexUpper   = mb_strtoupper($incomingData['sex'], 'UTF-8'); // MALE / FEMALE

        try {
            $user = User::create([
                'email'      => $incomingData['email'],
                'username'   => $incomingData['username'],
                'password'   => Hash::make($incomingData['password']),
                'identifier' => 3,
                'role'       => 'user',
            ]);

            // AUTO-CALCULATE AGE
            $dob = Carbon::parse($incomingData['birthdate']);
            $age = $dob->age;

            // Create profile (stored as UPPERCASE)
            $user->profile()->create([
                'fname'      => $firstName,
                'mname'      => $middleName,
                'lname'      => $lastName,
                'contact_no' => $incomingData['contact_no'],
                'sex'        => $sexUpper,           // MALE / FEMALE
                'birthdate'  => $incomingData['birthdate'],
                'age'        => $age,
            ]);

            session()->flash('success', 'User account created successfully!');

            // reset form fields including birthdate + age
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
                'birthdate',
                'age',
            ]);

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
        }
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
        $accounts = User::with('profile')
            ->where('identifier', 3)
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';

                $query->where(function ($q) use ($term) {
                    $q->where('email', 'like', $term)
                      ->orWhere('username', 'like', $term);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.admin-accounts', [
            'accounts' => $accounts
        ]);
    }
}
