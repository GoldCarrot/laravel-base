<?php
/**
 * Created date 09.02.2021
 * @author Sergey Tyrgola <ts@Goldcarrot\Base.ru>
 */

namespace Goldcarrot\Base\Interfaces;


interface CrudFactoryInterface
{
    public function createRepository(): RepositoryInterface;

    public function createService(): CrudServiceInterface;

    public function createValidator(): ValidatorInterface;

    public function createPresenter($model): PresenterInterface;
}
