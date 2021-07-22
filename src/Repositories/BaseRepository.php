<?php

namespace GoldcarrotLaravel\Base\Repositories;


use GoldcarrotLaravel\Base\Exceptions\TypeException;
use GoldcarrotLaravel\Base\Interfaces\RepositoryInterface;
use GoldcarrotLaravel\Base\Traits\RepositoryQueryConditions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    use RepositoryQueryConditions;

    protected string $keyName = 'id';
    protected int $defaultLimit = 20;
    private $model;

    /**
     * BaseRepository constructor.
     * @throws TypeException
     */
    public function __construct()
    {
        $model = new($this->modelClass());

        $this->model = $model instanceof Model
            ? $model
            : throw new TypeException('Method modelClass() of ' . static::class . ' must return ' . Model::class . ' instance');
    }

    abstract protected function modelClass(): string;

    public function one($id): Model|Builder|null
    {
        return $this->byKey($this->query(), $id)->first();
    }

    private function byKey(Builder $query, $key): Builder
    {
        return $query->where($this->qualifyColumn($this->keyName), $key);
    }

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(): Collection|array
    {
        return $this->query()->get();
    }

    public function oneActive($id): Model|Builder|null
    {
        return $this->byKey($this->active(), $id)->first();
    }

    protected function active(): Builder
    {
        return $this->query();
    }

    public function allActive(): Collection|array
    {
        return $this->active()->get();
    }

    public function searchActive(array $params = []): Collection|array
    {
        return $this->search($params, true);
    }

    public function search(array $params = [], $active = false): Collection|array
    {
        return $this->withParams($active ? $this->active() : $this->query(), $params)->get();
    }

    protected function withParams(Builder $query, array $params = []): Builder
    {
        $query->where(function (Builder $query) use ($params) {
            foreach ($params as $param => $value) {
                $this->withCondition($query, is_int($param) ? $value : [$param => $value]);
            }
        });

        return $query;
    }

    public function findActive(array $params = []): Model|Builder|null
    {
        return $this->find($params, true);
    }

    public function find(array $params = [], $active = false): Model|Builder|null
    {
        return $this->withParams($active ? $this->active() : $this->query(), $params)->first();
    }

    public function paginateActive(array $params = [], int $limit = null, $pageName = 'page'): LengthAwarePaginatorInterface|LengthAwarePaginator
    {
        return $this->paginate($params, $limit, true, $pageName);
    }

    public function paginate(array $params = [], int $limit = null, bool $active = false, $pageName = 'page'): LengthAwarePaginatorInterface|LengthAwarePaginator
    {
        return $this
            ->withParams($active ? $this->active() : $this->query(), $params)
            ->paginate($limit ?: $this->defaultLimit, ['*'], $pageName);
    }

    protected function qualifyColumn($column): string
    {
        return $this->model->qualifyColumn($column);
    }
}
