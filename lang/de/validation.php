<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validierungs-Sprachzeilen
    |--------------------------------------------------------------------------
    |
    | Die folgenden Sprachzeilen enthalten die Standard-Fehlermeldungen, die von
    | der Validator-Klasse verwendet werden. Einige dieser Regeln haben mehrere
    | Versionen, wie z.B. die Größenregeln. Fühlen Sie sich frei, diese
    | Meldungen hier anzupassen.
    |
    */

    'accepted' => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url' => 'Das Feld :attribute ist keine gültige URL.',
    'after' => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'after_or_equal' => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha' => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das Feld :attribute darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num' => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => 'Das Feld :attribute muss ein Array sein.',
    'before' => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Feld :attribute muss ein Datum vor oder gleich :date sein.',
    'between' => [
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
        'file' => 'Das Feld :attribute muss zwischen :min und :max Kilobytes groß sein.',
        'string' => 'Das Feld :attribute muss zwischen :min und :max Zeichen lang sein.',
        'array' => 'Das Feld :attribute muss zwischen :min und :max Elemente enthalten.',
    ],

    'confirmed' => 'Die :attribute Bestätigung stimmt nicht überein.',
    'date' => 'Das Feld :attribute ist kein gültiges Datum.',
    'date_format' => 'Das Feld :attribute entspricht nicht dem Format :format.',
    'different' => 'Die Felder :attribute und :other müssen unterschiedlich sein.',
    'digits' => 'Das Feld :attribute muss :digits Ziffern lang sein.',
    'digits_between' => 'Das Feld :attribute muss zwischen :min und :max Ziffern lang sein.',
    'dimensions' => 'Das Feld :attribute hat ungültige Bildabmessungen.',

    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',

    'file' => 'Das Feld :attribute muss eine Datei sein.',
    'filled' => 'Das Feld :attribute ist erforderlich.',
    'image' => 'Das Feld :attribute muss ein Bild sein.',

    'in_array' => 'Das Feld :attribute existiert nicht in :other.',
    'integer' => 'Das Feld :attribute muss eine ganze Zahl sein.',
    'ip' => 'Das Feld :attribute muss eine gültige IP-Adresse sein.',
    'json' => 'Das Feld :attribute muss eine gültige JSON-Zeichenkette sein.',
    'max' => [
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'file' => 'Das Feld :attribute darf nicht größer als :max Kilobytes sein.',
        'string' => 'Das Feld :attribute darf nicht länger als :max Zeichen sein.',
        'array' => 'Das Feld :attribute darf nicht mehr als :max Elemente enthalten.',
    ],
    'mimes' => 'Das Feld :attribute muss eine Datei vom Typ: :values sein.',
    'mimetypes' => 'Das Feld :attribute muss eine Datei vom Typ: :values sein.',
    'min' => [
        'numeric' => 'Das Feld :attribute muss mindestens :min sein.',
        'file' => 'Das Feld :attribute muss mindestens :min Kilobytes groß sein.',
        'string' => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
        'array' => 'Das Feld :attribute muss mindestens :min Elemente enthalten.',
    ],

    'numeric' => 'Das Feld :attribute muss eine Zahl sein.',

    'regex' => 'Das Format des Feldes :attribute ist ungültig.',

    'required_with_all' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',

    'same' => 'Die Felder :attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => 'Das Feld :attribute muss :size sein.',
        'file' => 'Das Feld :attribute muss :size Kilobytes groß sein.',
        'string' => 'Das Feld :attribute muss :size Zeichen lang sein.',
        'array' => 'Das Feld :attribute muss :size Elemente enthalten.',
    ],
    'string' => 'Das Feld :attribute muss eine Zeichenkette sein.',
    'timezone' => 'Das Feld :attribute muss eine gültige Zeitzone sein.',

    'url' => 'Das Format des Feldes :attribute ist ungültig.',

    /*
    |--------------------------------------------------------------------------
    | Benutzerdefinierte Validierungsattribute
    |--------------------------------------------------------------------------
    |
    | Die folgenden Sprachzeilen werden verwendet, um Attribut-Platzhalter
    | mit benutzerfreundlicheren Begriffen zu ersetzen, wie z.B. "E-Mail-Adresse"
    | statt "email". Dies hilft uns, die Meldungen etwas übersichtlicher zu machen.
    |
    */

    'attributes' => [],

    // Interne Validierungslogik für Panel
    'internal' => [
        'variable_value' => ':env Variable',
        'invalid_password' => 'Das angegebene Passwort ist für dieses Konto ungültig.',
    ],
];
