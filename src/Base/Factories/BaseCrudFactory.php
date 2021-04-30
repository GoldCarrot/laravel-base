<?php
/**
 * Created date 09.02.2021
 * @author Sergey Tyrgola <ts@Goldcarrot\Base.ru>
 */

namespace Goldcarrot\Base\Factories;


use Goldcarrot\Base\Interfaces\CrudFactoryInterface;
use Goldcarrot\Base\Interfaces\CrudServiceInterface;
use Goldcarrot\Base\Interfaces\PresenterInterface;
use Goldcarrot\Base\Interfaces\RepositoryInterface;
use Goldcarrot\Base\Interfaces\ValidatorInterface;
use InvalidArgumentException;

abstract class BaseCrudFactory implements CrudFactoryInterface
{
    protected string $repositoryClass;
    protected string $serviceClass;
    protected string $validatorClass;
    protected string $presenterClass;

    private function requireMissingClass($property): void
    {
        throw new InvalidArgumentException("You should specify $property property in " . static::class . " class");
    }

    public function createRepository(): RepositoryInterface
    {
        empty($this->repositoryClass) && $this->requireMissingClass('repositoryClass');
        return app($this->repositoryClass);
    }

    public function createService(): CrudServiceInterface
    {
        empty($this->serviceClass) && $this->requireMissingClass('serviceClass');
        return app($this->serviceClass);
    }

    public function createValidator(): ValidatorInterface
    {
        empty($this->validatorClass) && $this->requireMissingClass('validatorClass');
        return app($this->validatorClass);
    }

    public function createPresenter($model): PresenterInterface
    {
        empty($this->presenterClass) && $this->requireMissingClass('presenterClass');
        return app($this->presenterClass, ['model' => $model]);
    }
}
