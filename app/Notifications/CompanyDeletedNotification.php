<?php

namespace App\Notifications;

use Illuminate\Support\HtmlString;
use Illuminate\Bus\Queueable;
#use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Helper;

class CompanyDeletedNotification extends Notification
{
    use Queueable;

    protected $company=null;
    protected $auth_user=null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($company,$auth_user)
    {
        $this->company = $company;
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
                    ->subject(__('Account aziendale di Studio aggiornato'))
                    ->line(__("Un nuovo account aziendale dello studio è stato aggiornato da :auth_role :auth_email. I dettagli sono i seguenti.",["auth_role"=>$auth_role_name,"auth_email"=>$this->auth_user->email]))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s', "Azienda", $this->company->company_name)))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s', 'Tipo di azienda', $this->company->company_type)))
                    ->line(new HtmlString(sprintf('<strong>%s</strong>: %s', 'Partita IVA/codice fiscale', $this->company->vat_tax)));

                    
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
