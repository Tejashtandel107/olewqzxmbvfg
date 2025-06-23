<?php
namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\LineIncomeMonthlyService;
use Carbon\Carbon;

class ClientInvoiceExport implements FromCollection, WithHeadings, WithMapping,WithStyles,Responsable
{
    use Exportable;
    protected $filter;
    private $fileName = 'invoice.xlsx';
    private $writerType = Excel::XLSX;  
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct(array $filter){
        $this->filter = $filter;
    }
    public function collection(){
        $collection = (new LineIncomeMonthlyService)->getAll($this->filter)
                    ->where('invoice_number','!=',null)
                    ->filter(function ($item) {
                        return $item->price > 0 || $item->total_bonus > 0;
                    });   
                      
        return $collection;
    }

    public function headings(): array{
        return [
            'Invoice Date',
            'Invoice Number',
            'Estimate Number',
            'Invoice Status',
            'Customer Name',
            'Is Tracked For MOSS',
            'Due Date',
            'Expected Payment Date',
            'PurchaseOrder',
            'Template Name',
            'Currency Code',
            'Exchange Rate',
            'Item Name',
            'SKU',
            'Item Desc',
            'Quantity',
            'Item Price',
            'Usage unit',
            'Discount',
            'Expense Reference ID',
            'Is Inclusive Tax',
            'Discount Amount',
            'Item Tax1',
            'Item Tax1 Type',
            'Item Tax1 %',
            'Project Name',
            'Notes',
            'Terms & Conditions',
            'PayPal',
            'Authorize.Net',
            'Payflow Pro',
            'Stripe',
            '2Checkout',
            'Braintree',
            'Forte',
            'WorldPay',
            'Payments Pro',
            'Square',
            'WePay',
            'GoCardless',
            'Partial Payments',
            'Sales person',
            'Shipping Charge',
            'Adjustment',
            'Adjustment Description',
            'Discount Type',
            'Is Discount Before Tax',
            'Entity Discount Percent',
            'Entity Discount Amount',
            'Payment Terms',
            'Payment Terms Label',
            'Is Digital Service',
            'Branch Name',
            'Warehouse Name',
            'CF.Transporter_Name',
        ];
    }

    public function map($lineIncome): array{
        return [
            Carbon::parse($lineIncome->getRawOriginal('invoice_date'))->format('d/m/Y'),
            $lineIncome->getRawOriginal('invoice_number'),
            '',
            'Draft', 
            $lineIncome->name, 
            '',
            '',
            '',
            '',
            'Semplice',
            'EUR',
            '',
            'Outsourcing Contabilità Monthly Service',
            '',
            '',
            1,
            strval($lineIncome->total_amount),
            '',
            "0",
            '',
            'FALSE',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
            'false',
        ];
    } 

    public function styles(Worksheet $sheet){
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
