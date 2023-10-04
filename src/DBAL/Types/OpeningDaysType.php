<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class OpeningDaysType extends AbstractEnumType
{
    public const MONDAY = 'monday';
    public const TUESDAY = 'tuesday';
    public const WEDNESDAY = 'wednesday';
    public const THURSDAY = 'thursday';
    public const FRIDAY = 'friday';
    public const SATURDAY = 'saturday';
    public const SUNDAY = 'sunday';

    public static array $choices = [
        self::MONDAY => 'lundi',
        self::TUESDAY => 'mardi',
        self::WEDNESDAY => 'mercredi',
        self::THURSDAY => 'jeudi',
        self::FRIDAY => 'vendredi',
        self::SATURDAY => 'samedi',
        self::SUNDAY => 'dimanche'
    ];

}