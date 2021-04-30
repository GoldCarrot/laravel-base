<?php


namespace Goldcarrot\Traits;


use Illuminate\Database\Eloquent\Builder;

trait RepositoryAliasKey
{
    protected string $aliasKeyName = 'alias';

    protected function aliasOrId(Builder $query, $key, $aliasKeyName = 'alias', $keyName = 'id'): Builder
    {
        $keyName = $this->qualifyColumn($keyName);
        $aliasKeyName = $this->qualifyColumn($aliasKeyName);


        $query->where(function (Builder $query) use ($key, $keyName, $aliasKeyName) {
            $query
                ->where($keyName, $key)
                ->orWhere($aliasKeyName, $key);
        });

        return $query;
    }

    protected function byKey(Builder $query, $key): Builder
    {
        return $this->aliasOrId($query, $key, $this->aliasKeyName, $this->keyName);
    }
}