<?php
	return [
		'PAGINATION'=>30,
		'DB_DATE_FORMAT' => 'Y-m-d h:i A',
		'DATE_TIME_FORMAT' => 'd/m/Y h:i A',
		'DATE_FORMAT' => 'd/m/Y',
		'DEFAULT_IMG_HOLDER' => '/assets/images/place-holder.svg',
		'DEFAULT_IMG_PROFILE'=>'/assets/images/default-avatar.png',
		'CUSTOMER_PROFILE_PATH' => 'customer/profile',
		'ROLE_SUPER_ADMIN_ID'=>1,
		'ROLE_ACCOUNT_MANAGER_ID'=>2,
		'ROLE_CLIENT_ID'=>3,
		'ROLE_OPERATOR_ID'=>4,
		'USER_PROFILE_PATH' => 'profile',
		'COMPANY_TYPES' => [
			"Ordinaria" => "Ordinaria",
			"Semplificata" => "Semplificata",
			"Professionista" => "Professionista",
			"Forfettario" => "Forfettario"
		],
		'PETTY_CASH_BOOK_TYPES' => [
			"Banca" => "Banca",
			"Cassa" => "Cassa",
			"Carta Credito" => "Carta Credito",
			"Carta Prepagata" => "Carta Prepagata"
		],
		'STUDIO_PRICING_TYPES'=>[
			'fixed_price'=>"Prezzo Fisso",
			'fixed_price_with_milestone'=>'Prezzo Fisso con Pietra Miliare',
			'per_righe'=>"Per Righe",
			'per_registrazioni' => "Per Registrazioni"
		],	
		'OPERATOR_PRICING_TYPES'=>[
			'salary_plus_bonus'=>"Costo Stipendio più bonus",
			'per_righe_and_per_registrazioni'=>"Costo per Righe e Registrazioni"
		],
		'ACCOUNT_MANAGER_PRICING_TYPES'=>[
			'salary_plus_bonus'=>"Costo Stipendio più bonus",
			'per_righe_and_per_registrazioni'=>"Costo per Righe e Registrazioni"
		],	
		'BUSINESS_TYPES' => [
			"Agricoltura" =>"Agricoltura",
			"Commercio" => "Commercio",
			"Edile" => "Edile",
			"Produzione" => "Produzione",
			"Professionista" => "Professionista",
			"Ristorante" => "Ristorante",
			"Servizio" => "Servizio"
		],
		'BANK_INFO' => [
			[
				"title"=> "Albania","account_number" => "AL1820511344010099CLPRCFEURQ","name" =>"BANKA KOMBËTARE TREGTARE",
				"city" => "Tirana","address" => "BKT - DEGA IBRAHIM RUGOVA - TIRANA","swift_code" => "NCBAALTX",
				"beneficiary_name" => "HR ALBA SHPK","country" => "ALBANIA"
			],
			[
				"title"=> "SEPA","account_number" => "LT863500010017684980","name" =>"'Paysera LT', UAB",
				"city" => "Tirana","address" => "Pilaitės pr. 16, LT-04352, Vilnius, Lithuania","swift_code" => "EVIULT2VXXX",
				"beneficiary_name" => "HR Alba Sh p k","country" => "LITHUANIA"
			]
		]
	]
?>
