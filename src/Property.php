<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\VCard;

class Property
{
    public const FIELD_SEPARATOR = ';';
    public const LIST_SEPARATOR = ',';
    public const PARAMETER_SEPARATOR = ';';
    public const PROPERTY_SEPARATOR = ':';
    public const EQUALS = '=';
    private const LINE_LENGTH = 75;

    public function __construct(private string $name, private array|int|string $value, private array $parameters = [])
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): string
    {
        $parameters = [];

        /** @var array|string $value */
        foreach ($this->parameters as $parameter => $value) {
            $parameters[] = $parameter
                . self::EQUALS
                . (is_array($value) ? '"' . implode(self::LIST_SEPARATOR, $value) . '"' : $value)
            ;
        }

        return empty($parameters)
            ? ''
            : self::PARAMETER_SEPARATOR . implode(self::PARAMETER_SEPARATOR, $parameters)
        ;
    }

    public function getValue(): string
    {
        if (is_array($this->value)) {
            $fields = [];

            foreach ($this->value as $field) {
                $fields[] = is_array($field) ? implode(self::LIST_SEPARATOR, $field) : $field;
            }

            return implode(self::FIELD_SEPARATOR, $fields);
        }

        return (string) $this->value;
    }

    public function render(): string
    {
        return $this->fold(
            $this->getName()
            . $this->getParameters()
            . self::PROPERTY_SEPARATOR
            . $this->getValue()
        );
    }

    private function fold(string $line): string
    {
        if (strlen($line) > self::LINE_LENGTH) {
            $folded = [];

            $chars = mb_str_split($line);
            $byteCount = 0;
            $fold = '';

            do {
                $char = array_shift($chars);
                $byteCount += strlen($char);

                if ($byteCount <= self::LINE_LENGTH) {
                    $fold .= $char;
                } else {
                    $folded[] = $fold;
                    $fold = ' ' . $char;
                    $byteCount = strlen($fold);
                }
            } while (count($chars) > 0);
            $folded[] = $fold;

            $line = implode("\r\n", $folded);
        }

        return $line;
    }
}
