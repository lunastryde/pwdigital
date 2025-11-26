<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SupportThread;
use App\Models\SupportMessage;

class StaffSupportChat extends Component
{
    use WithFileUploads;

    public $threads = [];
    public ?int $selectedThreadId = null;
    public ?int $lastMessageId = null;

    // IMPORTANT: use a different name; "messages" is used by Livewire validation
    public $chatMessages = [];

    public string $body = '';
    public $attachment = null;

    public ?string $errorMessage = null;
    public bool $confirmingDelete = false;

    public function mount()
    {
        $user = auth()->user();
        if (! $user || $user->identifier == 3) {
            abort(403); // only staff/admin
        }

        $this->loadThreads();

        if (count($this->threads) > 0) {
            $first = $this->threads[0];
            $this->selectedThreadId = $first->id;
            $this->assignIfUnassigned();
            $this->loadMessages();
        }
    }

    public function loadThreads()
    {
        $this->threads = SupportThread::with('user')
            ->whereHas('messages')
            ->orderByRaw("FIELD(status, 'open','pending','resolved','closed')")
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get();
    }

    public function loadMessages()
    {
        if (! $this->selectedThreadId) {
            $this->chatMessages = [];
            return;
        }

        $messages = SupportMessage::where('thread_id', $this->selectedThreadId)
            ->orderBy('created_at')
            ->get();

        $latestId = $messages->last()->id ?? null;

        $this->chatMessages = $messages;

        if ($latestId !== null && $latestId !== $this->lastMessageId) {
            $this->lastMessageId = $latestId;
            $this->dispatch('scroll-staff-chat');
        }
    }


    // Called by wire:poll
    public function pollData()
    {
        $this->loadThreads();
        $this->loadMessages();
    }

    public function selectThread(int $threadId)
    {
        $this->selectedThreadId = $threadId;
        $this->assignIfUnassigned();
        $this->loadMessages();
    }

    protected function assignIfUnassigned()
    {
        if (! $this->selectedThreadId) {
            return;
        }

        $thread = SupportThread::find($this->selectedThreadId);
        if (! $thread) {
            return;
        }

        if (is_null($thread->staff_id)) {
            $thread->staff_id = auth()->id();
            if ($thread->status !== 'resolved') {
                $thread->status = 'pending';
            }
            $thread->save();
        }
    }

    public function sendMessage()
    {
        $this->errorMessage = null;

        if (! $this->selectedThreadId) {
            $this->addError('body', 'No thread selected.');
            return;
        }

        $this->validate([
            'body' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120',
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx'
            ],
        ]);

        $trimmed = trim($this->body ?? '');

        if ($trimmed === '' && ! $this->attachment) {
            $this->addError('body', 'Type a message or attach a file.');
            return;
        }

        if ($this->body === '' && ! $this->attachment) {
            $this->addError('body', 'Type a message or attach a file.');
            return;
        }

        $path = null;
        $originalName = null;
        $mime = null;
        $size = null;

        if ($this->attachment) {
            $file = $this->attachment;
            $path = $file->store('chat-attachments', 'public');
            $originalName = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $size = $file->getSize();
        }

        SupportMessage::create([
            'thread_id'                => $this->selectedThreadId,
            'sender_id'                => auth()->id(),
            'sender_is_staff'          => true,
            'body'                     => $trimmed !== '' ? $trimmed : null,
            'attachment_path'          => $path,
            'attachment_original_name' => $originalName,
            'attachment_mime'          => $mime,
            'attachment_size'          => $size,
        ]);

        // update thread timestamp / status
        $thread = SupportThread::find($this->selectedThreadId);
        if ($thread) {
            $thread->last_message_at = now();
            if ($thread->status !== 'resolved') {
                $thread->status = 'pending';
            }
            $thread->save();
        }

        $this->body = '';
        $this->attachment = null;

        $this->loadMessages();
        $this->loadThreads();
    }

    public function markResolved()
    {
        if (! $this->selectedThreadId) {
            return;
        }

        $thread = SupportThread::find($this->selectedThreadId);
        if (! $thread) {
            return;
        }

        $thread->status = 'resolved';
        $thread->save();

        $this->loadThreads();
    }

    public function deleteThread()
    {
        if (! $this->selectedThreadId) {
            return;
        }

        $thread = SupportThread::find($this->selectedThreadId);

        if (! $thread) {
            return;
        }

        if ($thread->status !== 'resolved') {
            $this->addError('body', 'You can only delete resolved threads.');
            $this->confirmingDelete = false;
            return;
        }

        // Delete the thread (messages cascade-delete)
        $thread->delete();

        // Reset state
        $this->selectedThreadId = null;
        $this->chatMessages = [];
        $this->lastMessageId = null;
        $this->confirmingDelete = false;

        // Reload threads
        $this->loadThreads();

        // Auto-select next thread if any
        if ($this->threads->count() > 0) {
            $first = $this->threads->first();
            $this->selectedThreadId = $first->id;
            $this->assignIfUnassigned();
            $this->loadMessages();
        }
    }

    public function confirmDelete()
    {
        if (! $this->selectedThreadId) {
            return;
        }

        $thread = SupportThread::find($this->selectedThreadId);
        if (! $thread) {
            return;
        }

        // Only allow delete when resolved
        if ($thread->status !== 'resolved') {
            $this->addError('body', 'You can only delete resolved threads.');
            return;
        }

        $this->confirmingDelete = true;
    }
    
    public function cancelDelete()
    {
        $this->confirmingDelete = false;
    }

    public function render()
    {
        return view('livewire.staff-support-chat');
    }
}
