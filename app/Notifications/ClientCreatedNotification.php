<?php

namespace App\Notifications;

use Illuminate\Support\HtmlString;
use Illuminate\Bus\Queueable;
#use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Helper;

class ClientCreatedNotification extends Notification
{
    use Queueable;

    protected $client=null;
    protected $auth_user=null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($client,$auth_user)
    {
        $this->client = $client;
        $this->auth_user = $auth_user;
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
        $auth_role_name = Helper::getRoleName($this->auth_user->role_id);

        return (new MailMessage)
                    ->subject(__('New client account created'))
                    ->line(__("Una nuova società di studio è stata creata dall'utente :auth :auth email. I dettagli sono i seguenti.",["auth_role"=>$auth_role_name,"auth_email"=>$this->auth_user->email]))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s', __('Name'), $this->client->name)))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s', __('E-mail'), $this->client->email)));
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
