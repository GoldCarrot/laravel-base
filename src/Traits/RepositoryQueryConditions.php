<?php


namespace Goldcarrot\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RepositoryQueryConditions
{
    protected array $searchableColumns = ['id'];

    private string $defaultCondition = 'Is';

    private array $conditions = [
        'Is',
        'IsNot',
        'Gt',
        'Gte',
        'Lt',
        'Lte',
        'Like',
    ];

    private array $statelessConditions = [
        'IsNull',
        'IsNotNull',
    ];

    private array $hardConditions = [
        'Between',
        'IsNotBetween',
    ];

    private array $operators = [
        'Is' => '=',
        'IsNot' => '!=',
        'Gt' => '>',
        'Gte' => '>=',
        'Lt' => '<',
        'Lte' => '<=',
        'Like' => 'like',
    ];

    protected function withCondition(Builder $query, mixed $parameter): Builder
    {
        if (is_string($parameter) && $data = $this->recognize($this->statelessConditions, $parameter)) {
            $this->addStatelessCondition($query, ...$data);
        }
        if (is_array($parameter)) {
            $condition = array_key_first($parameter);
            $value = $parameter[$condition];

            if (!is_array($value) && $data = $this->recognize($this->conditions, $condition)) {
                [$column, $condition, $boolean] = $data;
                $this->addCondition($query, $column, $condition, $value, $boolean);
            }

            if (is_array($value) && $data = $this->recognize($this->hardConditions, $condition)) {
                [$column, $condition, $boolean] = $data;
                $this->addHardCondition($query, $column, $condition, $value, $boolean);
            }
        }

        return $query;
    }

    protected function recognize(array $conditions, $string): ?array
    {
        $conditionsPattern = collect($conditions)->join('|');

        $searchableColumnsCollection = collect($this->searchableColumns)->map(fn($column) => Str::camel($column));
        $andColumns = $searchableColumnsCollection->join('|');
        $orColumns = $searchableColumnsCollection->map(fn($column) => Str::ucfirst($column))->join('|');

        $andPattern = "/^($andColumns)($conditionsPattern)?$/";
        $orPattern = "/^or($orColumns)($conditionsPattern)?$/";

        if (preg_match($andPattern, $string, $parameters)) {
            return [Str::snake($parameters[1]), $parameters[2] ?? $this->defaultCondition, 'and'];
        }

        if (preg_match($orPattern, $string, $parameters)) {
            return [Str::snake($parameters[1]), $parameters[2] ?? $this->defaultCondition, 'or'];
        }

        return null;
    }

    private function addStatelessCondition(Builder $query, $column, $condition, $boolean = 'and'): bool
    {
        if (in_array($condition, $this->statelessConditions, true)) {
            switch ($condition) {
                case 'IsNull' :
                    $query->whereNull($column, $boolean);
                    break;
                case 'IsNotNull' :
                    $query->whereNotNull($column, $boolean);
                    break;
            }
            return true;
        }

        return false;
    }

    private function addCondition(Builder $query, $column, $condition, $value, $boolean = 'and'): bool
    {
        if ($operator = Arr::get($this->operators, $condition)) {
            $query->where($column, $operator, $value, $boolean);

            return true;
        }

        return false;
    }

    private function addHardCondition(Builder $query, $column, $condition, $values, $boolean = 'and'): bool
    {
        if (Arr::has($this->hardConditions, $condition)) {
            switch ($condition) {
                case 'Between':
                    $query->whereBetween($column, $values, $boolean);
                    break;
                case 'IsNotBetween':
                    $query->whereNotBetween($column, $values, $boolean);
                    break;
            }
            return true;
        }

        return false;
    }
}