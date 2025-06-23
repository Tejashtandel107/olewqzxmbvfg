<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LineIncomeMonthly;
use App\Facades\Helper;

class LineIncomeInvoice extends Notification
{
    use Queueable;
    protected $attachments;
    protected $lineIncomeMonthly;
    protected $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LineIncomeMonthly $lineIncomeMonthly,array $attachments)
    {
        $this->attachments = $attachments;
        $this->lineIncomeMonthly = $lineIncomeMonthly;
        $this->settings = Helper::getSettings();
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
        $totalOutstanding = $this->lineIncomeMonthly->total_amount - $this->lineIncomeMonthly->total_paid;
        $subject = sprintf('Fattura %s - Outsourcing Contabilità(%s)',$this->lineIncomeMonthly->getRawOriginal('invoice_number'),$this->settings['company_name']);
        $mail = (new MailMessage)
                ->greeting(sprintf('Gentile %s,',$notifiable->name))
                ->subject($subject)
                ->cc($this->lineIncomeMonthly->user->profile->additional_emails)
                ->line(new HtmlString(sprintf('Grazie per aver scelto <strong>%s</strong> In allegato troverà la sua fattura:', config('app.name'))))
                ->line(new HtmlString('<b>Dettagli della Fattura:</b>'))
                ->line(sprintf('\- Numero Fattura: %s',$this->lineIncomeMonthly->getRawOriginal('invoice_number')))
                ->line(sprintf('\- Data Fattura: %s',$this->lineIncomeMonthly->invoice_date))
                ->line(sprintf('\- Importo Dovuto: €%s', number_format($totalOutstanding, 2, ',', '.')))
                ->line(new HtmlString(sprintf('Se ha domande o dubbi riguardanti questa fattura, non esiti a contattarci all’indirizzo <a href="mailto:%s">%s</a>.',"accounts@hralba.com","accounts@hralba.com")));
                    
        foreach($this->attachments as $attachments) {
            $mail->attach($attachments);
        }
        return $mail;
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
