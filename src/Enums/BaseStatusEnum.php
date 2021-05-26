<?php
/**
 * Created date 04.08.2020
 * @author Sergey Tyrgola <ts@GoldcarrotLaravel\Base.ru>
 */

namespace GoldcarrotLaravel\Base\Enums;

/**
 * Class BaseStatusEnums
 * @package GoldcarrotLaravel\Base\Enums
 *
 * @method static bool isActive($status)
 * @method static bool isInactive($status)
 * @method static bool isBanned($status)
 * @method static bool isDeleted($status)
 */
class BaseStatusEnum extends BaseEnum
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const BANNED = 'banned';
    public const DELETED = 'deleted';

    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::BANNED,
            self::DELETED,
        ];
    }
}
