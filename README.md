# vCard
The vCard library provides the ability to create
[vCard (RFC 6350)](https://datatracker.ietf.org/doc/html/rfc6350) strings.

## Creating vCard files
The vCard library allows creation of vCards in an object-oriented way.

The library provides class constants to provide code completion and improve code readability.

To create a vCard, create a new Vcard object then add properties to it; multiple properties with the same name are supported.

Properties that comprise multiple fields delimited by a SEMICOLON character (e.g., N and ADR) can be specified as array; empty fields **must** be given. If a property field is a list it may be specified as an array.

The following are equivalent:
```php
->addProperty(
    Vcard::PROPERTY_N,
    'Perreault;Simon;;;ing. jr,M.Sc.'
)
```
```php
->addProperty(
    Vcard::PROPERTY_N,
    [
        'Perreault',
        'Simon',
        '',
        '',
        'ing. jr,M.Sc.'
    ]
)
```

Provide property parameters as an array where the key is the parameter name (hint: use class constants) and the value is the value; if the value is a list it may be specified as an array.  

The following are equivalent:
```php
[
    Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI,
    Vcard::PARAMETER_TYPE => '"' . Vcard::TYPE_WORK . ',' . Vcard::TYPE_VOICE . '"',
    Vcard::PARAMETER_PREF => 1
]
```
```php
[
    Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI,
    Vcard::PARAMETER_TYPE => [
        Vcard::TYPE_WORK,
        Vcard::TYPE_VOICE
    ],
    Vcard::PARAMETER_PREF => 1
]
```

Finally, call the Vcard's render() method.

### Note
The library does **not** do any checking for validity; it is possible to create a string that is not a valid vCard. 

### Example
The following example creates the vCard at
[section 8 of RFC6350](https://datatracker.ietf.org/doc/html/rfc6350#section-8).

```php
$vCard = (new Vcard())
    ->addProperty(
        Vcard::PROPERTY_FN,
        'Simon Perreault'
    )
    ->addProperty(
        Vcard::PROPERTY_N,
        [
            'Perreault',
            'Simon',
            '',
            '',
            'ing. jr,M.Sc.'
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_BDAY,
        '--0203'
    )    
    ->addProperty(
        Vcard::PROPERTY_ANNIVERSARY,
        '20090808T1430-0500'
    )
    ->addProperty(
        Vcard::PROPERTY_GENDER,
        Vcard::GENDER_MALE
    )
    ->addProperty(
        Vcard::PROPERTY_LANG,
        'fr',
        [
            Vcard::PARAMETER_PREF => 1
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_LANG,
        'en',
        [
            Vcard::PARAMETER_PREF => 2
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_ORG,
        'Viagenie',
        [
            Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_ADR,
        [
            '',
            'Suite D2-630',
            '2875 Laurier',
            'Quebec',
            'QC',
            'G1V 2M2',
            'Canada'
        ],
        [
            Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_TEL,
        'tel:+1-418-656-9254;ext=102',
        [
            Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI,
            Vcard::PARAMETER_TYPE => [
                Vcard::TYPE_WORK,
                Vcard::TYPE_VOICE
            ],
            Vcard::PARAMETER_PREF => 1
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_TEL,
        'tel:+1-418-262-6501',
        [
            Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI,
            Vcard::PARAMETER_TYPE => [
                Vcard::TYPE_WORK,
                Vcard::TYPE_CELL,
                Vcard::TYPE_VOICE,
                Vcard::TYPE_VIDEO,
                Vcard::TYPE_TEXT
            ],
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_EMAIL,
        'simon.perreault@viagenie.ca',
        [Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK]
    )
    ->addProperty(
        Vcard::PROPERTY_GEO,
        'geo:46.772673,-71.282945',
        [
            Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK
        ]
    )
    ->addProperty(
        Vcard::PROPERTY_KEY,
        'http://www.viagenie.ca/simon.perreault/simon.asc',
        [
            Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK,
            Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI
        ]
    )
    ->addProperty(Vcard::PROPERTY_TZ, '-0500')
    ->addProperty(
        Vcard::PROPERTY_URL,
        'http://nomis80.org',
        [
            Vcard::PARAMETER_TYPE => Vcard::TYPE_HOME
        ]
    )
    ->render()
;
```

## Import vCard
Import an vCard file using Vcard's static import() method:

```php
$vcard = Vcard::import($string);
```

## Installation
The preferred way to install the library is with [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist beastbytes/icalendar
```

or add

```json
"beastbytes/vcard": "^1.0.0"
```

to the 'require' section of your composer.json.

## Testing
### Unit testing
The package is tested with PHPUnit. To run the tests:

```
./vendor/bin/phpunit
```

### Static analysis
The code is statically analyzed with Psalm. To run static analysis:

```
./vendor/bin/psalm
```

## License
The vCard Library is free software. It is released under the terms of the BSD License. For license information see the [LICENSE](LICENSE.md) file.
