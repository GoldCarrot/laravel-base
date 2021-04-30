<?php
/**
 * Created date 18.01.2021
 * @author Sergey Tyrgola <ts@Goldcarrot\Base.ru>
 */

namespace Goldcarrot\Base\Presenters;

use Goldcarrot\Base\Interfaces\PresenterInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

abstract class BasePresenter implements PresenterInterface, Arrayable
{
    protected Model $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    abstract public function toArray(): array;

    public function toPublicArray(): array
    {
        return $this->toArray();
    }
}
