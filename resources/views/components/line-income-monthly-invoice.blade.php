<?php
    $settings = Helper::getSettings();
    $bank_info = config('constant.BANK_INFO');
    
    $client = $lineIncomeMonthly->user;
    $clientCountry = ($client->profile->country->country_name) ?? "";

    $companyAddress = [
        $settings['company_address'],
        implode(" ",[$settings['company_postal_code'],$settings['company_city'],$settings['company_province']]),
        $settings['country_name'],
    ];
    $clientAddress = [
        $client->profile->address,
        implode(" ", [$client->profile->postal_code, $client->profile->city, $client->profile->province]),
        $clientCountry
    ];
    $filteredClientAddress = array_filter($clientAddress, function($value) {
        return $value !== null && $value !== '';
    });
    $totalAmount = Helper::formatAmount($lineIncomeMonthly->total_amount);
?>
<div class="invoice-main w-100">
    <table class="w-100 invoice-header-main" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="company-logo"><img src="{{asset('/assets/images/outsourcing-white-black.png')}}" style="max-width:200px" alt ="Outsourcing Contabilità logo"></td>
            <td class="text-right company-details">
                <div class="company-name"><b>{{$settings['company_name']}}</b></div>
                <div class="mb-0"><?php echo implode("<br>", $companyAddress); ?></div>
                <div>NUIS (VAT) {{$settings['company_vat_tax']}}</div>
            </td>
        </tr>
    </table>
    <table class="invoice-title-main w-100" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><div class="spacer"><!-- Spacer --></div></td>
			<td class="invoice-title">FATTURA</td>
			<td><div class="spacer"><!-- Spacer --></div></td>
		</tr>
    </table>
    <table class="w-100 client-details-main" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="client-details">
                <div>Fattura a</div>    
                <div><b>{{$client->name}}</b></div>
                <div><?php echo (!empty($filteredClientAddress)) ? implode("<br>", $filteredClientAddress) :'';?></div>
                <div>Codice Fiscale/IVA {{$client->profile->vat_number}}</div>
            </td>
            <td class="invoice-details">
                <table class="w-100" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><div class="item-label">N. fattura</div></td>
                        <td class="last-column"><div class="item-value">{{$lineIncomeMonthly->getRawOriginal('invoice_number') ? $lineIncomeMonthly->getRawOriginal('invoice_number') : 'DRAFT'}}</div></td>
                    </tr>
                    <tr>
                        <td><div class="item-label">Data fattura</div> </td>
                        <td class="last-column"><div class="item-value">{{$lineIncomeMonthly->invoice_date}}</div></td>
                    </tr>
                    <tr>
                        <td><div class="item-label">Data di scadenza</div></td>
                        <td class="last-column"><div class="item-value">{{$lineIncomeMonthly->invoice_due_date}}</div></td>
                    </tr>
                    <tr class="last-row">
                        <td><div class="item-label">Riferimento</div></td>
                        <td class="last-column"><div class="item-value">{{$lineIncomeMonthly->invoice_reference}}</div></td>
                    </tr> 
                </table>
            </td>
        </tr>
    </table>
    <div class="w-100 invoice-line-items">
        <table class="w-100" cellspacing="0" cellpadding="0">
            <tr>
                <th class="text-left"><div class="item-label">Servizio & Descrizione</div></th>
                <th class="text-center"><div class="item-label">Qtà</div></th>
                <th class="text-right"><div class="item-label">Tariffa</div></th>
                <th class="text-right last-column"><div class="item-label">Importo</div></th>
            </tr>
            <tr class="last-row">
                <td	class="text-left"><div class="item-value">Outsourcing Contabilità Monthly Service</div></td>
                <td	class="text-center"><div class="item-value">1</div></td>
                <td	class="text-right"><div class="item-value">{!!$totalAmount!!}</div></td>
                <td	class="text-right last-column"><div class="item-value">{!!$totalAmount!!}</div></td>
            </tr>
        </table>
    </div>
    <table class="w-100 invoice-totals" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td><!-- Spacer --></td>
            <td class="invoice-subtotal-heading"><b>Totale</b></td>
            <td class="invoice-subtotal"><b>{!!$totalAmount!!}</b></td>
        </tr>
        <tr>
            <td><!-- Spacer --></td>
            <td class="invoice-subtotal-heading"><b>Totale pagato</b></td>
            <td class="invoice-subtotal"><b>{!!Helper::formatAmount($lineIncomeMonthly->total_paid) !!}</b></td>
        </tr>
        <tr>
            <td><!-- Spacer --></td>
            <td class="invoice-subtotal-heading"><b>Saldo da pagare</b></td>
            <td class="invoice-subtotal"><b>{!!Helper::formatAmount($lineIncomeMonthly->total_outstanding) !!}</b></td>
        </tr>
    @if((!empty($showBankFees)))
        <tr>
            <td><!-- Spacer --></td>
            <td class="invoice-subtotal-heading"><b>Commissioni bancarie</b></td>
            <td class="invoice-subtotal"><b>{!!Helper::formatAmount($lineIncomeMonthly->bank_fees) !!}</b></td>
        </tr>
    @endif
    </table>
    <div class="terms-conditions-title"><b>Termini e condizioni</b></div>
@foreach ($bank_info as $key=>$bank)
    <div class="company-bank-details">      
        <b>IBAN {{$key+1}} {{$bank['title']}}:</b>
        <div>IBAN (EUR): {{$bank['account_number']}}</div>
        <div>SWIFT CODE: {{$bank['swift_code']}}</div>
        <div>Beneficiary Name: {{$bank['beneficiary_name']}}</div>
        <div>Nome Banca: {{$bank['name']}}</div>
        <div>Nome Conto: {{$bank['country']}}</div>
        <div>Indirizzo della banca: {{$bank['address']}}</div>
    </div>
@endforeach
</div>



