<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SupportThread;
use App\Models\SupportMessage;

class UserSupportChat extends Component
{
    use WithFileUploads;

    public ?SupportThread $thread = null;
    public $chatMessages = [];
    public ?int $lastMessageId = null;

    public string $body = '';
    public $attachment = null; // Livewire temp uploaded file

    public ?string $errorMessage = null;
    public bool $isResolved = false;

    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        // Find existing open/pending thread or create one
        $this->thread = SupportThread::where('user_id', $user->id)
            ->whereIn('status', ['open', 'pending'])
            ->orderByDesc('id')
            ->first();

        if (! $this->thread) {
            $this->thread = SupportThread::create([
                'user_id'        => $user->id,
                'status'         => 'open',
                'last_message_at'=> now(),
            ]);
        }

        $this->isResolved = $this->thread->status === 'resolved';
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->thread->refresh();
        $this->isResolved = $this->thread->status === 'resolved';

        $messages = SupportMessage::where('thread_id', $this->thread->id)
            ->orderBy('created_at')
            ->get();

        $latestId = $messages->last()->id ?? null;

        $this->chatMessages = $messages;

        if ($latestId !== null && $latestId !== $this->lastMessageId) {
            $this->lastMessageId = $latestId;
            $this->dispatch('scroll-user-chat');
        }
    }


    // Called by wire:poll
    public function refreshMessages()
    {
        $this->loadMessages();
    }

    public function sendMessage()
    {
        // ❗ Block sending if thread is resolved
        if ($this->isResolved) {
            $this->addError('body', 'This support conversation has been resolved and is read-only.');
            return;
        }
        
        $this->errorMessage = null;

        $this->validate([
            'body' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120', // 5 MB
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
            'thread_id'                 => $this->thread->id,
            'sender_id'                 => auth()->id(),
            'sender_is_staff'           => false,
            'body'                      => $trimmed !== '' ? $trimmed : null,
            'attachment_path'           => $path,
            'attachment_original_name'  => $originalName,
            'attachment_mime'           => $mime,
            'attachment_size'           => $size,
        ]);

        $this->thread->update([
            'last_message_at' => now(),
        ]);

        // Reset form
        $this->body = '';
        $this->attachment = null;

        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.user-support-chat');
    }
}
