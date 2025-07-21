<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;
use Illuminate\Support\Carbon; // en haut de ton fichier

class DueDateApproachingNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('⏰ Alerte - Échéance proche')
        ->line("La tâche \"{$this->task->task}\" approche de sa date limite : " . Carbon::parse($this->task->due_date)->format('d/m/Y'))
        ->action('Voir les tâches', url('/user/tasks'))
        ->line('Merci de la traiter rapidement.');
}
}
