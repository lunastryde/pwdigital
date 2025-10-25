<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementManager extends Component
{
    public $announcements;
    public ?Announcement $editing = null;

    public string $title = '';
    public string $content = '';
    public ?string $expires_at = null;
    public bool $is_published = true;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'expires_at' => 'nullable|date',
            'is_published' => 'required|boolean',
        ];
    }

    public function mount(): void
    {
        $this->loadAnnouncements();
    }

    public function loadAnnouncements(): void
    {
        $this->announcements = Announcement::with('poster')->latest()->get();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'account_id' => Auth::id(),
            'posted_by' => Auth::user()->name, // Storing name directly as in your table
            'title' => $this->title,
            'content' => $this->content,
            'is_published' => $this->is_published,
            'published_at' => now(),
            'expires_at' => $this->expires_at ?: null, // Set to null if empty
        ];

        if ($this->editing) {
            $this->editing->update($data);
        } else {
            Announcement::create($data);
        }

        $this->resetForm();
        $this->loadAnnouncements();
    }

    public function edit(int $announcementId): void
    {
        $this->editing = Announcement::find($announcementId);
        $this->title = $this->editing->title;
        $this->content = $this->editing->content;
        $this->is_published = $this->editing->is_published;
        // Format date for the datetime-local input field
        $this->expires_at = $this->editing->expires_at ? $this->editing->expires_at->format('Y-m-d\TH:i') : null;
    }

    public function delete(int $announcementId): void
    {
        Announcement::find($announcementId)->delete();
        $this->loadAnnouncements();
    }

    public function resetForm(): void
    {
        $this->editing = null;
        $this->reset(['title', 'content', 'expires_at', 'is_published']);
    }

    public function render()
    {
        return view('livewire.announcement-manager');
    }
}