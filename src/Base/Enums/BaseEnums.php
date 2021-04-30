<?php

namespace Goldcarrot\Base\Enums;


use Goldcarrot\Base\Interfaces\EnumsInterface;
use Goldcarrot\Base\Str;
use InvalidArgumentException;
use ReflectionClass;


abstract class BaseEnums implements EnumsInterface
{

    abstract public static function keys(): array;

    public static function labels(): array
    {
        return array_combine(static::keys(), static::keys());
    }

    public static function contains($key): bool
    {
        return in_array($key, static::keys(), true);
    }

    public static function label($key): mixed
    {
        $labels = static::labels();

        if (array_key_exists($key, $labels)) {
            return $labels[$key];
        }

        throw new InvalidArgumentException("Unknown enum: $key");
    }

    public function __call(string $method, array $parameters)
    {
        if ($compareCallArguments = $this->getCompareCallArguments($method, $parameters)) {
            return $this->compare(...$compareCallArguments);
        }
    }

    public static function __callStatic(string $method, array $parameters)
    {
        return (new static)->$method(...$parameters);
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
