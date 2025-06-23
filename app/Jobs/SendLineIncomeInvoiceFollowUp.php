<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LineIncomeMonthly;
use App\Notifications\LineIncomeInvoiceFollowUp1;
use App\Services\LineClientService;
use App\Services\LineIncomeMonthlyService;
use Exception;

class SendLineIncomeInvoiceFollowUp 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lineIncomeMonthly;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LineIncomeMonthly $lineIncomeMonthly)
    {
        $this->lineIncomeMonthly = $lineIncomeMonthly;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->lineIncomeMonthly->isInvoiceReady()) {
            if(!empty($this->lineIncomeMonthly->invoice_number)){
                try{
                    $attachments = [];
                    $request =[
                        "from_date" => $this->lineIncomeMonthly->pricing_date->format('d/m/Y'),
                        "to_date" => $this->lineIncomeMonthly->pricing_date->endOfMonth()->format('d/m/Y'),
                        "client_id" =>  $this->lineIncomeMonthly->user_id,
                    ];
                    $lineClientService = new LineClientService;
                    $lineIncomeMonthlyService = new LineIncomeMonthlyService;

                    $excelFilePath = $lineClientService->exportClientLines($request);
                    $pdfFilePath = $lineIncomeMonthlyService->exportInvoice($this->lineIncomeMonthly,$request);
                    if($excelFilePath){
                        $attachments[] = $excelFilePath;
                    }
                    if($pdfFilePath){
                        $attachments[] = $pdfFilePath;
                    }
                
                    $this->lineIncomeMonthly->user->notify(new LineIncomeInvoiceFollowUp1($this->lineIncomeMonthly,$attachments));
                    $status =  "invoice follow up-1 sent";
                    $notes  = "La fattura di follow-up 1 è stata inviata correttamente allo studio.";
                    $this->lineIncomeMonthly->invoice_due_date = now()->addDays(3);
                    $this->lineIncomeMonthly->invoice_status = 'unpaid';
                    $this->lineIncomeMonthly->save();
                }
                catch(Exception $e){
                    $status = "send invoice: server error";
                    $notes  = "Il sistema non è riuscito a inviare una fattura allo studio.";
                }
                
                foreach($attachments as $attchment){
                    @unlink($attchment);
                }
            } 
            else{
                $status =  "send invoice: failed";
                $notes = "Il sistema non è riuscito a inviare una fattura perché il numero di fattura non è disponibile. Si prega di generare prima il numero della fattura.";
            }
        }  
        else{
            $status ="not ready yet";
            $notes = "La fattura non è ancora pronta per l'invio.";
        }

        if(isset($status)){
            $this->lineIncomeMonthly->createStatusHistory($status,$notes);
            return ($status=='invoice follow up-1 sent') ? true : false;
        }
        return false;
    }
}
