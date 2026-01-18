<?php

namespace Rickgoemans\LaravelEnumHelpers\Traits;

use Exception;
use Illuminate\Support\Str;
use UnitEnum;

trait HasEnumHelpers
{
    /**
     * Overwrite me to have custom translation keys.
     */
    public static function baseLabel(): ?string
    {
        return null;
    }

    public static function labels(bool $translate = true): array
    {
        return array_map(fn (UnitEnum|self $enum): string => $enum->label($translate), self::cases());
    }

    /**
     * Returns the options as key => value.
     */
    public static function options(): array
    {
        return array_map(fn (UnitEnum|self $enum) => $enum->value, self::cases());
    }

    /**
     * Returns the options as key => value.
     */
    public static function keyValueOptions(): array
    {
        return array_combine(
            array_map(fn (UnitEnum|self $enum) => $enum->name, self::cases()),
            array_map(fn (UnitEnum|self $enum) => $enum->value, self::cases())
        );
    }

    /**
     * Returns a random case.
     */
    public static function randomCase(): static
    {
        $cases = self::cases();
        $randomKey = array_rand($cases);

        return $cases[$randomKey];
    }

    /**
     * Return the options for Laravel Nova (Multi)Select fields.
     *
     * @see https://nova.laravel.com/docs/4.0/resources/fields.html#select-field
     */
    public static function optionsForSelect(): array
    {
        return array_combine(
            array_map(fn (UnitEnum|self $enum) => $enum->value, self::cases()),
            array_map(fn (UnitEnum|self $enum) => $enum->label(), self::cases())
        );
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

    public function label(bool $translate = true): string
    {
        $baseLabel = static::baseLabel();

        if (is_null($baseLabel)) {
            $baseLabel = Str::snake(class_basename(static::class), '.');
        }

        $key = Str::lower($baseLabel).".{$this->name}";

        return $translate
            ? __($key)
            : $key;
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
}
