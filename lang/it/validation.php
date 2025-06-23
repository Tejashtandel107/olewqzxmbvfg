<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    
    'accepted' => "L':attribute deve essere accettato.",
    'accepted_if' => "L':attribute deve essere accettato quando :other è :value.",
    'active_url' => "L':attribute non è un URL valido.",
    'after' => "L':attribute deve essere una data dopo :date.",
    'after_or_equal' => "L':attribute deve essere una data successiva o uguale a :date.",
    'alpha' => "L':attribute deve contenere solo lettere.",
    'alpha_dash' => "L':attribute deve contenere solo lettere, numeri, trattini e underscore.",
    'alpha_num' => "L':attribute deve contenere solo lettere e numeri.",
    'array' => "L':attribute deve essere un array.",
    'ascii' => "L':attribute deve contenere solo caratteri alfanumerici e simboli a byte singolo.",
    'before' => "L':attribute deve essere una data precedente :date.",
    'before_or_equal' => "L':attribute deve essere una data precedente o uguale a :date.",
    'between' => [
        'array' => "L':attribute deve avere tra :min e :max elementi.",
        'file' => "L':attribute deve essere tra :min e :max kilobyte.",
        'numeric' => "L':attribute deve essere tra :min e :max.",
        'string' => "L':attribute deve essere tra :min e :max caratteri.",
    ],
    'boolean' => "L':attribute il campo deve essere vero o falso.",
    'confirmed' => "L':attribute la conferma non corrisponde.",
    'current_password' => 'La password non è corretta.',
    'date' => "L':attribute non è una data valida.",
    'date_equals' => "L':attribute deve essere una data uguale a :date.",
    'date_format' => "L':attribute non corrisponde al formato :format.",
    'decimal' => "L':attribute deve avere :decimal decimali.",
    'declined' => "L':attribute deve essere rifiutato.",
    'declined_if' => "L':attribute deve essere rifiutato quando :other è :value.",
    'different' => "L':attribute e :other deve essere diverso.",
    'digits' => "L':attribute deve essere :digits cifre.",
    'digits_between' => "L':attribute deve essere tra :min e :max cifre.",
    'dimensions' => "Il :attribute ha dimensioni dell'immagine non valide.",
    'distinct' => "L':attribute campo ha un valore duplicato.",
    'doesnt_end_with' => "L':attribute potrebbe non terminare con uno dei seguenti: :values.",
    'doesnt_start_with' => "L':attribute potrebbe non iniziare con uno dei seguenti: :values.",
    'email' => "L':attribute deve essere un indirizzo email valido.",
    'ends_with' => "L':attribute deve terminare con uno dei seguenti: :values.",
    'enum' => "L'selezionato :attribute è invalido.",
    'exists' => "L'selezionato :attribute è invalido.",
    'file' => "L':attribute deve essere un file.",
    'filled' => "L':attribute il campo deve avere un valore.",
    'gt' => [
        'array' => "L':attribute deve avere più di :value elementi.",
        'file' => "L':attribute deve essere maggiore di :value kilobyte.",
        'numeric' => "L':attribute deve essere maggiore di :value.",
        'string' => "L':attribute deve essere maggiore di :value caratteri.",
    ],
    'gte' => [
        'array' => "L':attribute deve avere :value oggetti o altro.",
        'file' => "L':attribute deve essere maggiore o uguale a :value kilobyte.",
        'numeric' => "L':attribute deve essere maggiore o uguale a :value.",
        'string' => "L':attribute deve essere maggiore o uguale a :value caratteri.",
    ],
    'image' => "L':attribute deve essere un`immagine.",
    'in' => "L'selezionato :attribute è invalido.",
    'in_array' => "L':attribute campo non esiste in :other.",
    'integer' => "L':attribute deve essere un numero intero.",
    'ip' => "L':attribute deve essere un indirizzo IP valido.",
    'ipv4' => "L':attribute deve essere un indirizzo IPv4 valido.",
    'ipv6' => "L':attribute deve essere un indirizzo IPv6 valido.",
    'json' => "L':attribute deve essere una stringa JSON valida.",
    'lowercase' => "L':attribute deve essere minuscolo.",
    'lt' => [
        'array' => "L':attribute deve avere meno di :value elementi.",
        'file' => "L':attribute deve essere inferiore a :value kilobyte.",
        'numeric' => "L':attribute deve essere inferiore a :value.",
        'string' => "L':attribute deve essere inferiore a :value caratteri.",
    ],
    'lte' => [
        'array' => "L':attribute non deve avere più di :value elementi.",
        'file' => "L':attribute deve essere minore o uguale a :value kilobyte.",
        'numeric' => "L':attribute deve essere minore o uguale a :value.",
        'string' => "L':attribute deve essere minore o uguale a :value caratteri.",
    ],
    'mac_address' => "L':attribute deve essere un indirizzo MAC valido.",
    'max' => [
        'array' => "L':attribute non deve avere più di :max elementi.",
        'file' => "L':attribute non deve essere maggiore di :max kilobyte.",
        'numeric' => "L':attribute non deve essere maggiore di :max.",
        'string' => "L':attribute non deve essere maggiore di :max personaggi.",
    ],
    'max_digits' => "L':attribute non deve avere più di :max digits.",
    'mimes' => "L':attribute deve essere un file di tipo: :values.",
    'mimetypes' => "L':attribute deve essere un file di tipo: :values.",
    'min' => [
        'array' => "L':attribute deve avere almeno :min elementi.",
        'file' => "L':attribute deve essere almeno :min kilobyte.",
        'numeric' => "L':attribute deve essere almeno :min.",
        'string' => "L':attribute deve essere almeno :min personaggi.",
    ],
    'min_digits' => "L':attribute deve avere almeno :min digits.",
    'multiple_of' => "L':attribute deve essere un multiplo di :value.",
    'not_in' => "L'selezionato :attribute è invalido.",
    'not_regex' => "L':attribute il formato non è valido.",
    'numeric' => "L':attribute deve essere un numero.",
    'password' => [
        'letters' => "L':attribute deve contenere almeno una lettera.",
        'mixed' => "L':attribute deve contenere almeno una lettera maiuscola e una minuscola.",
        'numbers' => "L':attribute deve contenere almeno un numero.",
        'symbols' => "L':attribute deve contenere almeno un simbolo.",
        'uncompromised' => "L'dato :attribute è apparso in una fuga di dati. Si prega di scegliere un altro :attribute.",
    ],
    'present' => "L':attribute il campo deve essere presente.",
    'prohibited' => "L':attribute campo è vietato.",
    'prohibited_if' => "L':attribute campo è vietato quando :other è :value.",
    'prohibited_unless' => "L':attribute campo è vietato a meno che :other è dentro :values.",
    'prohibits' => "Il :attribute campo vieta :other dall'essere presente.",
    'regex' => "L':attribute il formato non è valido.",
    'required' => "L':attribute il campo è obbligatiorio.",
    'required_array_keys' => "L':attribute il campo deve contenere voci per: :values.",
    'required_if' => "L':attribute il campo è obbligatorio quando :other è :value.",
    'required_if_accepted' => "L':attribute il campo è obbligatorio quando :other è accettato.",
    'required_unless' => "L':attribute il campo è obbligatorio a meno che :other è dentro :values.",
    'required_with' => "L':attribute il campo è obbligatorio quando :values è presente.",
    'required_with_all' => "L':attribute il campo è obbligatorio quando :values sono presenti.",
    'required_without' => "L':attribute il campo è obbligatorio quando :values non è presente.",
    'required_without_all' => "L':attribute il campo è obbligatorio quando nessuno di :values sono presenti.",
    'same' => "L':attribute e :other deve combaciare.",
    'size' => [
        'array' => "L' :atrribute deve contenere elementi :size.",
        'file' => "L':attribute deve essere :size kilobyte.",
        'numeric' => "L':attribute deve essere :size.",
        'string' => "L':attribute deve essere :size caratteri.",
    ],
    'starts_with' => "L':attribute deve iniziare con uno dei seguenti: :values.",
    'string' => "L':attribute deve essere una stringa.",
    'timezone' => "L':attribute deve essere un fuso orario valido.",
    'unique' => "L':attribute è già stato preso.",
    'uploaded' => "L':attribute Impossibile caricare.",
    'uppercase' => "L':attribute deve essere maiuscolo.",
    'url' => "L':attribute deve essere un URL valido.",
    'ulid' => "L':attribute deve essere un ULID valido.",
    'uuid' => "L':attribute deve essere un UUID valido.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
