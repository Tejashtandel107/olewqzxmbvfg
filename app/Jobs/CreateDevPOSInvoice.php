<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DevPOSApiService;
use App\Models\LineIncomeMonthly;

class CreateDevPOSInvoice
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
    public function handle(DevPOSApiService $devPOSApiService)
    {
        $statusFormat = "devPOS invoice: %s";
        if($this->lineIncomeMonthly->isInvoiceReady()){
            if(empty($this->lineIncomeMonthly->invoice_number) || $this->lineIncomeMonthly->invoice_status == 'cancel'){
                $result = $devPOSApiService->createInvoice($this->lineIncomeMonthly);

                if (isset($result["invoiceNumber"])) {
                    $status = sprintf($statusFormat, "success");
                    $notes = "la fattura devpos ha creato successo";
                    $this->lineIncomeMonthly->invoice_number = $result['invoiceNumber'];
                    $this->lineIncomeMonthly->invoice_date = now();
                    if (in_array($this->lineIncomeMonthly->user_id, [153, 5])) {
                        $this->lineIncomeMonthly->invoice_due_date = now()->addDays(30);
                    } else {
                        $this->lineIncomeMonthly->invoice_due_date = now()->addDays(3);
                    }
                    $this->lineIncomeMonthly->invoice_status = 'unpaid';
                    $this->lineIncomeMonthly->save();
                } 
                elseif (isset($result["error"])) {
                    $notes = [];
                    $status = sprintf($statusFormat, $result["message"]);
                    foreach ($result["errors"] as $error) {
                        $notes[] = $error; 
                    }
                    $notes = implode("\r\n",$notes);
                } 
            }
            else{
                $status = sprintf($statusFormat, "already created");
                $notes = "La fattura è già stata creata nel portale devPos.";
            }
        }
        else{
            $status = sprintf($statusFormat, "not ready yet");
            $notes = "La fattura non è ancora pronta per l'invio.";
        }
        
        if(isset($status)){
            $this->lineIncomeMonthly->createStatusHistory($status,$notes);
        }
        return (isset($result["id"])) ? true : false;
    }
}
