<?php

namespace VanguardLTE\Support\Enum;

class UserStatus
{
    const UNCONFIRMED = 'Unconfirmed';
    const ACTIVE = 'Active';
    const BANNED = 'Banned';
    const DELETED = 'Deleted';
    const REJECTED = 'Rejected';
    const JOIN = 'Join';

    public static function lists()
    {
        return [
            self::ACTIVE => trans('app.'.self::ACTIVE),
            self::BANNED => trans('app.'. self::BANNED),
            self::DELETED => trans('app.'. self::DELETED),
            self::REJECTED => trans('app.'. self::REJECTED),
            self::JOIN => trans('app.'. self::JOIN),
            self::UNCONFIRMED => trans('app.' . self::UNCONFIRMED)
        ];
    }

    public static function bgclass()
    {
        return
        [
            self::ACTIVE => 'bg-primary',
            self::BANNED => 'bg-danger',
            self::DELETED => 'bg-warning',
            self::REJECTED => 'bg-primary',
            self::JOIN => 'bg-info',
            self::UNCONFIRMED => 'bg-default'
        ];
    }


}
