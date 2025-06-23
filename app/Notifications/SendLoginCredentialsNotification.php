<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;


class SendLoginCredentialsNotification extends Notification
{
    use Queueable;
    public $user;
    public $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$request)
    {
        $this->user=$user;
        $this->request=$request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        $appName = config('app.name');
        return (new MailMessage)
                    ->subject(__('Credenziali di accesso'))
                    ->line('Ciao '.$this->user->name)
                    ->line('Il tuo account è stato creato con successo su '. $appName.'. I tuoi dati di accesso sono quelli indicati di seguito.')
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s',__('E-mail'), $this->user->email)))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s',__('Password'), $this->request->password)))
                    ->action('Collegamento di accesso', route('login'))
                    ->line('Per reimpostare la password, fare clic sul collegamento di reimpostazione della password indicato di seguito.')
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: <a href="%s">%s</a>', 'Collegamento per la reimpostazione della password', route('password.request'), 'Clicca qui per reimpostare la tua password')));
     }
    
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
