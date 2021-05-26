<?php
/**
 * Created date 12.01.2021
 * @author Sergey Tyrgola <ts@GoldcarrotLaravel\Base.ru>
 */

namespace GoldcarrotLaravel\Base\Interfaces;


interface EnumInterface
{
    public static function keys(): array;

    public static function labels(): array;
}
