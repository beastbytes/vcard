<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Vcard;

use InvalidArgumentException;

class Vcard
{
    public const GENDER_FEMALE = 'F';
    public const GENDER_MALE = 'M';
    public const GENDER_NONE = 'N';
    public const GENDER_NOT_APPLICABLE = 'N';
    public const GENDER_OTHER = 'O';
    public const GENDER_UNKNOWN = 'U';
    public const PARAMETER_ALTID = 'ALTID';
    public const PARAMETER_CALSCALE = 'CALSCALE';
    public const PARAMETER_GEO = 'GEO';
    public const PARAMETER_LANGUAGE = 'LANGUAGE';
    public const PARAMETER_MEDIATYPE = 'MEDIATYPE';
    public const PARAMETER_PID = 'PID';
    public const PARAMETER_PREF = 'PREF';
    public const PARAMETER_SORT_AS = 'SORT-AS';
    public const PARAMETER_TYPE = 'TYPE';
    public const PARAMETER_TZ = 'TZ';
    public const PARAMETER_VALUE = 'VALUE';
    public const PROPERTY_ADR = 'ADR';
    public const PROPERTY_ANNIVERSARY = 'ANNIVERSARY';
    public const PROPERTY_BDAY = 'BDAY';
    public const PROPERTY_CALADRURI = 'CALADRURI';
    public const PROPERTY_CALURI = 'CALURI';
    public const PROPERTY_CATEGORIES = 'CATEGORIES';
    public const PROPERTY_CLIENTPIDMAP = 'CLIENTPIDMAP';
    public const PROPERTY_EMAIL = 'EMAIL';
    public const PROPERTY_FBURL = 'FBURL';
    public const PROPERTY_FN = 'FN';
    public const PROPERTY_GENDER = 'GENDER';
    public const PROPERTY_GEO = 'GEO';
    public const PROPERTY_IMPP = 'IMPP';
    public const PROPERTY_KEY = 'KEY';
    public const PROPERTY_KIND = 'KIND';
    public const PROPERTY_LANG = 'LANG';
    public const PROPERTY_LOGO = 'LOGO';
    public const PROPERTY_MEMBER = 'MEMBER';
    public const PROPERTY_N = 'N';
    public const PROPERTY_NICKNAME = 'NICKNAME';
    public const PROPERTY_NOTE = 'NOTE';
    public const PROPERTY_ORG = 'ORG';
    public const PROPERTY_PHOTO = 'PHOTO';
    public const PROPERTY_PRODID = 'PRODID';
    public const PROPERTY_RELATED = 'RELATED';
    public const PROPERTY_REV = 'REV';
    public const PROPERTY_ROLE = 'ROLE';
    public const PROPERTY_SOUND = 'SOUND';
    public const PROPERTY_SOURCE = 'SOURCE';
    public const PROPERTY_TEL = 'TEL';
    public const PROPERTY_TITLE = 'TITLE';
    public const PROPERTY_TZ = 'TZ';
    public const PROPERTY_UID = 'UID';
    public const PROPERTY_URL = 'URL';
    public const PROPERTY_VERSION = 'VERSION';
    public const PROPERTY_XML = 'XML';
    public const VALUE_DATA_TYPE_BOOLEAN = 'boolean';
    public const VALUE_DATA_TYPE_DATE = 'date';
    public const VALUE_DATA_TYPE_DATE_AND_OR_TIME = 'date-and-or-time';
    public const VALUE_DATA_TYPE_DATE_TIME = 'date-time';
    public const VALUE_DATA_TYPE_FLOAT = 'float';
    public const VALUE_DATA_TYPE_INTEGER = 'integer';
    public const VALUE_DATA_TYPE_LANGUAGE_TAG = 'language-tag';
    public const VALUE_DATA_TYPE_TEXT = 'text';
    public const VALUE_DATA_TYPE_TIME = 'time';
    public const VALUE_DATA_TYPE_TIMESTAMP = 'timestamp';
    public const VALUE_DATA_TYPE_URI = 'uri';
    public const VALUE_DATA_TYPE_UTC_OFFSET = 'utc-offset';

    public const CALSCALE_GREGORIAN = 'gregorian';
    public const KIND_GROUP = 'group';
    public const KIND_INDIVIDUAL = 'individual';
    public const KIND_LOCATION = 'location';
    public const KIND_ORG = 'org';
    public const TYPE_ACQUAINTANCE = 'acquaintance';
    public const TYPE_AGENT = 'agent';
    public const TYPE_CELL = 'cell';
    public const TYPE_CHILD = 'child';
    public const TYPE_COLLEAGUE = 'colleague';
    public const TYPE_CONTACT = 'contact';
    public const TYPE_CO_RESIDENT = 'co-resident';
    public const TYPE_CO_WORKER = 'co-worker';
    public const TYPE_CRUSH = 'crush';
    public const TYPE_DATE = 'date';
    public const TYPE_EMERGENCY = 'emergency';
    public const TYPE_FAX = 'fax';
    public const TYPE_FRIEND = 'friend';
    public const TYPE_HOME = 'home';
    public const TYPE_KIN = 'kin';
    public const TYPE_ME = 'me';
    public const TYPE_MET = 'met';
    public const TYPE_MUSE = 'muse';
    public const TYPE_NEIGHBOR = 'neighbor';
    public const TYPE_PAGER = 'pager';
    public const TYPE_PARENT = 'parent';
    public const TYPE_SIBLING = 'sibling';
    public const TYPE_SPOUSE = 'spouse';
    public const TYPE_SWEETHEART = 'sweetheart';
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTPHONE = 'textphone';
    public const TYPE_VOICE = 'voice';
    public const TYPE_VIDEO = 'video';
    public const TYPE_WORK = 'work';

    public const BEGIN = 'BEGIN';
    public const END = 'END';
    public const NAME = 'VCARD';
    public const VERSION = '4.0';

    private const LINE_REGEX = '/^([A-Z]+)((;[-A-Z]+=(".+"|.+?))*):(.+)$/';
    private const VALUE_SPLIT_REGEX = '/(?<!\\\),/';

    private const CARDINALITY_ONE_MAY = '*1';
    private const CARDINALITY_ONE_MUST = '1';
    private const CARDINALITY_ONE_OR_MORE_MAY = '*';
    private const CARDINALITY_ONE_OR_MORE_MUST = '1*';

    private const CARDINALITY = [
        self::PROPERTY_ADR => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_ANNIVERSARY => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_BDAY => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_CALADRURI => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CALURI => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CATEGORIES => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_CLIENTPIDMAP => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_EMAIL => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_FBURL => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_FN => self::CARDINALITY_ONE_OR_MORE_MUST,
        self::PROPERTY_GENDER => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_GEO => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_IMPP => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_KEY => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_KIND => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_LANG => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_LOGO => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_MEMBER => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_N => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_NICKNAME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_NOTE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_ORG => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_PHOTO => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_PRODID => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_RELATED => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_REV => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_ROLE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_SOUND => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_SOURCE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TEL => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TITLE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TZ => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_UID => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_URL => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_VERSION => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_XML => self::CARDINALITY_ONE_OR_MORE_MAY,
    ];

    /**
     * @var array<string, Property|list<Property>> $properties
     */
    private array $properties = [];

    public function __construct()
    {
        $this->properties = [
            self::PROPERTY_VERSION => new Property(self::PROPERTY_VERSION, self::VERSION)
        ];
    }

    public function addProperty(string $name, array|int|string $value, array $parameters = []): self
    {
        $new = clone $this;

        if (
            self::CARDINALITY[$name] === self::CARDINALITY_ONE_MAY
            || self::CARDINALITY[$name] === self::CARDINALITY_ONE_MUST
        ) {
            $new->properties[$name] = new Property($name, $value, $parameters);
        } else {
            $new->properties[$name][] = new Property($name, $value, $parameters);
        }

        return $new;
    }

    /** @return array<string, list<Property>|Property> */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param string $name
     * @return list<Property>|Property|null
     */
    public function getProperty(string $name): array|Property|null
    {
        return $this->properties[$name] ?? null;
    }

    public function render(): string
    {
        /** @var array<string> $lines */
        $lines = [self::BEGIN . Property::PROPERTY_SEPARATOR . self::NAME];

        foreach (array_keys($this->properties) as $name) {
            if (is_array($this->properties[$name])) {
                foreach ($this->properties[$name] as $property) {
                    $lines[] = $property->render();
                }
            } else {
                $lines[] = $this
                    ->properties[$name]
                    ->render()
                ;
            }
        }

        $lines[] = self::END . Property::PROPERTY_SEPARATOR . self::NAME;

        return implode("\r\n", $lines) . "\r\n";
    }

    public static function import(string $import): Vcard
    {
        $import = str_replace(["\r\n", "\n\r", "\n", "\r"], "\n", $import);
        $import = str_replace(["\n ", "\n\t"], "", $import);
        $import = trim($import, "\n");
        $lines = explode("\n", $import);

        if (array_shift($lines) !== self::BEGIN . Property::PROPERTY_SEPARATOR . self::NAME) {
            throw new InvalidArgumentException('Invalid vCard');
        }

        array_pop($lines);

        $vcard = new self();

        do {
            $line = array_shift($lines);
            $vcard = self::importProperty($vcard, $line);
        } while (count($lines) > 0);

        return $vcard;
    }

    private static function importProperty(Vcard $vcard, string $line): Vcard
    {
        $property = [];
        if (!preg_match(self::LINE_REGEX, $line, $property)) {
            throw new InvalidArgumentException("Invalid vCard property: $line");
        }

        $parameters = (!empty($property[2])
            ? explode(Property::PARAMETER_SEPARATOR, substr($property[2], 1))
            : []
        );

        $keys = [];
        $values = [];
        foreach ($parameters as $parameter) {
            $parameter = explode(Property::EQUALS, $parameter);
            $keys[] = $parameter[0];
            $values[] = $parameter[1];
        }

        return $vcard->addProperty(
            $property[1],
            $property[5],
            array_combine($keys, $values)
        );
    }
}
