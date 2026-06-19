<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoActiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $customMessage = '')
    {
        $this->user = $user;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso de cuenta desactivada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $adminUser = auth()->user();
        $desactiveBy = [
            'role' => $adminUser->getRoleNames()->filter(fn($role) => $role != 'Cliente')[0] ?? 'Administrador',
            'name' => $adminUser->name ?? 'Sistema'
        ];

        return new Content(
            view: 'email.no-active',
            with: [
                'user' => $this->user,
                'customMessage' => $this->customMessage,
                'deactivatedBy' => $desactiveBy,
                'supportPhone ' => Setting::where('key', 'phone')->get('value'),
                'supportEmail ' => Setting::where('key', 'email_contact')->get('value'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
