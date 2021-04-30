<?php
/**
 * Created date 17.07.2020
 * @author Sergey Tyrgola <ts@Goldcarrot\Base.ru>
 */

namespace Goldcarrot\Base\Interfaces;


interface PresenterInterface
{
    public function __construct($model);

    public function toArray(): array;

    public function toPublicArray(): array;
}
