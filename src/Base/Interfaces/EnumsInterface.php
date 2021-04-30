<?php
/**
 * Created date 12.01.2021
 * @author Sergey Tyrgola <ts@Goldcarrot\Base.ru>
 */

namespace Goldcarrot\Base\Interfaces;


interface EnumsInterface
{
    public static function keys(): array;

    public static function labels(): array;
}
