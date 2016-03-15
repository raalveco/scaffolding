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

    'accepted'             => ':attribute debe ser aceptado.',
    'active_url'           => ':attribute no es una URL válida.',
    'after'                => ':attribute debe ser una fecha superior a :date.',
    'alpha'                => ':attribute solo debe contener letras.',
    'alpha_dash'           => ':attribute solo debe contener letras, números y guiones.',
    'alpha_num'            => ':attribute solo debe contener letras, números.',
    'array'                => ':attribute debe ser un conjunto.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
    'between'              => [
        'numeric' => ':attribute debe estar entre :min - :max.',
        'file'    => ':attribute debe pesar entre :min - :max kilobytes.',
        'string'  => ':attribute debe estar entre :min - :max caracteres.',
        'array'   => ':attribute debe tener entre :min - :max items.',
    ],
    'boolean'              => ':attribute deber ser un valor "true" o "false".',
    "confirmed"            => "La confirmación de :attribute no coincide.",
    "date"                 => ":attribute no es una fecha válida.",
    "date_format"          => ":attribute no corresponde al formato :format.",
    "different"            => ":attribute y :other deben ser diferentes.",
    "digits"               => ":attribute debe tener :digits dígitos.",
    "digits_between"       => ":attribute debe tener entre :min y :max dígitos.",
    "email"                => ":attribute no es un correo válido",
    "exists"               => ":attribute es inválido.",
    "image"                => ":attribute debe ser una imagen.",
    "in"                   => ":attribute es inválido.",
    "integer"              => ":attribute debe ser un número entero.",
    "ip"                   => ":attribute debe ser una dirección IP válida.",
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    "max"              => [
        "numeric" => ":attribute no debe ser mayor a :max.",
        "file"    => ":attribute no debe ser mayor que :max kilobytes.",
        "string"  => ":attribute no debe ser mayor que :max caracteres.",
        "array"   => ":attribute no debe tener más de :max elementos.",
    ],
    "mimes"            => ":attribute debe ser un archivo con formato: :values.",
    "min"              => [
        "numeric" => "El tamaño de :attribute debe ser de al menos :min.",
        "file"    => "El tamaño de :attribute debe ser de al menos :min kilobytes.",
        "string"  => ":attribute debe contener al menos :min caracteres.",
        "array"   => ":attribute debe tener al menos :min elementos.",
    ],
    "not_in"               => ":attribute es inválido.",
    "numeric"              => ":attribute debe ser numérico.",
    "regex"                => "El formato de :attribute es inválido.",
    "required"             => "El campo <b>:attribute</b> es obligatorio.",
    "required_if"          => "El campo :attribute es obligatorio cuando :other es :value.",
    "required_with"        => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_with_all"    => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_without"     => "El campo :attribute es obligatorio cuando :values no está presente.",
    "required_without_all" => "El campo :attribute es obligatorio cuando ninguno de :values estén presentes.",
    "same"                 => ":attribute y :other deben coincidir.",
    'size'                 => [
        'numeric' => 'El campo :attribute must be :size.',
        'file'    => 'El campo :attribute must be :size kilobytes.',
        'string'  => 'El campo :attribute must be :size characters.',
        'array'   => 'El campo :attribute must contain :size items.',
    ],
    'string'               => ':attribute debe ser una frase de texto.',
    'timezone'             => ':attribute debe ser una zona horaria válida.',
    "unique"               => "El :attribute ya ha sido registrado.",
    "url"                  => "El formato :attribute es inválido.",

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
