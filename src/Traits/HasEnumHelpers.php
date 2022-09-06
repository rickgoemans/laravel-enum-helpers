<?php

namespace Rickgoemans\LaravelEnumHelpers\Traits;

use Exception;
use UnitEnum;

trait HasEnumHelpers
{
    /**
     * Returns the options as key => value.
     */
    public static function options(): array
    {
        return array_map(fn (UnitEnum $enum) => $enum->value, self::cases());
    }

    /**
     * Returns the options as key => value.
     */
    public static function keyValueOptions(): array
    {
        return array_combine(
            array_map(fn (UnitEnum $enum) => $enum->name, self::cases()),
            array_map(fn (UnitEnum $enum) => $enum->value, self::cases())
        );
    }

    /**
     * Return the options for Laravel Nova (Multi)Select fields.
     *
     * @see https://nova.laravel.com/docs/4.0/resources/fields.html#select-field
     */
    public static function optionsForSelect(): array
    {
        return array_combine(
            array_map(fn (UnitEnum $enum) => $enum->value, self::cases()),
            array_map(fn (UnitEnum $enum) => $enum->label(), self::cases())
        );
    }

    public static function optionsForValidation(): string
    {
        return implode(',', array_map(fn (UnitEnum $enum) => $enum->value, self::cases()));
    }

    /**
     * Retrieve the Laravel Nova badge colors.
     * Options: "success", "info", "warning" and "danger"
     *
     * @see https://nova.laravel.com/docs/4.0/resources/fields.html#badge-field
     */
    public static function badgeColors(): array
    {
        return [
            null => 'info',
        ];
    }

    /**
     *  Define the order of all options by the key of the array
     */
    public static function order(): array
    {
        return [];
    }

    /** @throws Exception */
    public function isGreaterThan(self|string|int $compare): bool
    {
        return self::compare($compare) > 0;
    }

    /** @throws Exception */
    public function isLessThan(self|string|int $compare): bool
    {
        return self::compare($compare) < 0;
    }

    /** @throws Exception */
    public function isEqual(self|string|int $compare): bool
    {
        return self::compare($compare) === 0;
    }

    public function label(): string
    {
        return $this->value;
    }

    /** @throws Exception */
    protected function compare(self|string|int $compare): int
    {
        $order = self::order();

        // Lookup self
        $keySelf = array_search($this, $order);

        // Lookup compare
        $compareValue = $compare instanceof self
            ? $compare
            : self::tryFrom($compare);
        if (is_null($compareValue)) {
            throw new Exception("Invalid value: {$compare}");
        }
        $keyCompare = array_search($compareValue, $order);

        return $keyCompare <=> $keySelf;
    }

    /**
     * The base (translated path) label.
     */
    protected function baseLabel(string $baseKey): string
    {
        return __("{$baseKey}{$this->value}");
    }
}
