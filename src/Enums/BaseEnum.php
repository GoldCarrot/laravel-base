<?php

namespace GoldcarrotLaravel\Base\Enums;


use GoldcarrotLaravel\Base\Interfaces\EnumInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;


abstract class BaseEnum implements EnumInterface
{

    public static function contains($key): bool
    {
        return in_array($key, static::keys(), true);
    }

    abstract public static function keys(): array;

    public static function label($key): mixed
    {
        $labels = static::labels();

        if (array_key_exists($key, $labels)) {
            return $labels[$key];
        }

        throw new InvalidArgumentException("Unknown enum: $key");
    }

    public static function labels(): array
    {
        return array_combine(static::keys(), static::keys());
    }

    public static function __callStatic(string $method, array $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function __call(string $method, array $parameters)
    {
        if ($compareCallArguments = $this->getCompareCallArguments($method, $parameters)) {
            return $this->compare(...$compareCallArguments);
        }
    }

    private function getCompareCallArguments($method, $parameters): ?array
    {
        if (preg_match('/^(is(Not)?)([A-Za-z]+)$/', $method, $matches)) {
            return [$matches[1], $matches[3], ...$parameters];
        }

        return null;
    }

    private function compare($operator, $enum, $value): ?bool
    {
        if ($constantValue = (new ReflectionClass(static::class))->getConstant(Str::upper(Str::snake($enum)))) {
            switch ($operator) {
                case 'is':
                    return $constantValue === $value;
                case 'isNot':
                    return $constantValue !== $value;
            }
        }
        return null;
    }
}
