<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Vcard\Tests;

use BeastBytes\Vcard\Vcard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreateVcardTest extends TestCase
{
    #[DataProvider('vcardProvider')]
    public function test_vcard($vcard, $expected)
    {
        $this->assertSame(implode("\r\n", $expected) . "\r\n", $vcard->render());
    }

    public static function vcardProvider(): \Generator
    {
        foreach ([
            'simple_vcard' => [
                (new Vcard())
                    ->addProperty(Vcard::PROPERTY_KIND, Vcard::KIND_ORG)
                    ->addProperty(Vcard::PROPERTY_FN, 'ABC Marketing')
                    ->addProperty(Vcard::PROPERTY_ORG, 'ABC\, Inc.;North American Division;Marketing'),
                [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'KIND:org',
                    'FN:ABC Marketing',
                    'ORG:ABC\, Inc.;North American Division;Marketing',
                    'END:VCARD'
                ]
            ],
            'properties_with_same_name' => [
                (new Vcard())
                    ->addProperty(Vcard::PROPERTY_KIND, Vcard::KIND_GROUP)
                    ->addProperty(Vcard::PROPERTY_FN, 'Distribution list')
                    ->addProperty(Vcard::PROPERTY_MEMBER, 'mailto:subscriber1@example.com')
                    ->addProperty(Vcard::PROPERTY_MEMBER, 'xmpp:subscriber2@example.com')
                    ->addProperty(Vcard::PROPERTY_MEMBER, 'sip:subscriber3@example.com')
                    ->addProperty(Vcard::PROPERTY_MEMBER, 'tel:+1-418-555-5555'),
                [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'KIND:group',
                    'FN:Distribution list',
                    'MEMBER:mailto:subscriber1@example.com',
                    'MEMBER:xmpp:subscriber2@example.com',
                    'MEMBER:sip:subscriber3@example.com',
                    'MEMBER:tel:+1-418-555-5555',
                    'END:VCARD'
                ]
            ],
            'rfc_author_vcard' => [
                (new Vcard())
                    ->addProperty(
                        Vcard::PROPERTY_FN,
                        'Simon Perreault'
                    )
                    ->addProperty(
                        Vcard::PROPERTY_N,
                        'Perreault;Simon;;;ing. jr,M.Sc.'
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
                        ';Suite D2-630;2875 Laurier;Quebec;QC;G1V 2M2;Canada',
                        [
                            Vcard::PARAMETER_TYPE => Vcard::TYPE_WORK
                        ]
                    )
                    ->addProperty(
                        Vcard::PROPERTY_TEL,
                        'tel:+1-418-656-9254;ext=102',
                        [
                            Vcard::PARAMETER_VALUE => Vcard::VALUE_DATA_TYPE_URI,
                            Vcard::PARAMETER_TYPE => '"' . Vcard::TYPE_WORK . ',' . Vcard::TYPE_VOICE . '"',
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
                    ),
                [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'FN:Simon Perreault',
                    'N:Perreault;Simon;;;ing. jr,M.Sc.',
                    'BDAY:--0203',
                    'ANNIVERSARY:20090808T1430-0500',
                    'GENDER:M',
                    'LANG;PREF=1:fr',
                    'LANG;PREF=2:en',
                    'ORG;TYPE=work:Viagenie',
                    'ADR;TYPE=work:;Suite D2-630;2875 Laurier;Quebec;QC;G1V 2M2;Canada',
                    'TEL;VALUE=uri;TYPE="work,voice";PREF=1:tel:+1-418-656-9254;ext=102',
                    'TEL;VALUE=uri;TYPE="work,cell,voice,video,text":tel:+1-418-262-6501',
                    'EMAIL;TYPE=work:simon.perreault@viagenie.ca',
                    'GEO;TYPE=work:geo:46.772673,-71.282945',
                    'KEY;TYPE=work;VALUE=uri:http://www.viagenie.ca/simon.perreault/simon.asc',
                    'TZ:-0500',
                    'URL;TYPE=home:http://nomis80.org',
                    'END:VCARD'
                ]
            ],
            'rfc_author_vcard_using_field_arrays' => [
                (new Vcard())
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
                            Vcard::PARAMETER_TYPE => '"' . Vcard::TYPE_WORK . ',' . Vcard::TYPE_VOICE . '"',
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
                    ),
                [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'FN:Simon Perreault',
                    'N:Perreault;Simon;;;ing. jr,M.Sc.',
                    'BDAY:--0203',
                    'ANNIVERSARY:20090808T1430-0500',
                    'GENDER:M',
                    'LANG;PREF=1:fr',
                    'LANG;PREF=2:en',
                    'ORG;TYPE=work:Viagenie',
                    'ADR;TYPE=work:;Suite D2-630;2875 Laurier;Quebec;QC;G1V 2M2;Canada',
                    'TEL;VALUE=uri;TYPE="work,voice";PREF=1:tel:+1-418-656-9254;ext=102',
                    'TEL;VALUE=uri;TYPE="work,cell,voice,video,text":tel:+1-418-262-6501',
                    'EMAIL;TYPE=work:simon.perreault@viagenie.ca',
                    'GEO;TYPE=work:geo:46.772673,-71.282945',
                    'KEY;TYPE=work;VALUE=uri:http://www.viagenie.ca/simon.perreault/simon.asc',
                    'TZ:-0500',
                    'URL;TYPE=home:http://nomis80.org',
                    'END:VCARD'
                ]
            ]
        ] as $name => $yield) {
            yield $name => $yield;
        }
    }
}
