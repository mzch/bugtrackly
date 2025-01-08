<?php

namespace App\Notifications\Bug;

use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BugCommentCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Project $project, protected Bug $bug, protected array $dataMail)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[' . $this->project->name . ' ' . $this->bug->bug_id_formatted . '] : ' . $this->bug->title)
            ->markdown('emails.bug_comment.created', [
                'notifiable' => $notifiable,
                'project' => $this->project,
                'bug' => $this->bug,
                'dataMail' => $this->dataMail,
            ]);;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
