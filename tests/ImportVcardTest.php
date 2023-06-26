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

class ImportVcardTest extends TestCase
{
    #[DataProvider('vcardProvider')]
    public function test_vcard_import($vcard)
    {
        $imported = Vcard::import($vcard);

        $this->assertInstanceOf(Vcard::class, $imported);
        $this->assertSame($vcard, $imported->render());
    }

    public static function vcardProvider(): array
    {
        return [
            'simple vcard' => [
                implode("\r\n", [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'KIND:org',
                    'FN:ABC Marketing',
                    'ORG:ABC\, Inc.;North American Division;Marketing',
                    'END:VCARD'
                ]) . "\r\n"
            ],
            'many properties vcard' => [
                implode("\r\n", [
                    'BEGIN:VCARD',
                    'VERSION:4.0',
                    'KIND:group',
                    'FN:Distribution list',
                    'MEMBER:mailto:subscriber1@example.com',
                    'MEMBER:xmpp:subscriber2@example.com',
                    'MEMBER:sip:subscriber3@example.com',
                    'MEMBER:tel:+1-418-555-5555',
                    'END:VCARD'
                ]) . "\r\n"
            ],
            'rfc author vcard' => [
                implode("\r\n", [
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
                ]) . "\r\n"
            ]
        ];
    }
}
