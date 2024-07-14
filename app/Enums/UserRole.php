<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;

enum UserRole
{
    use InvokableCases, Names;

    case SUPERADMIN;
    case OPERATOR_UJIAN;
    case OPERATOR_SOAL;
    case PARTICIPANT;

    public static function getListUserCreateRoles()
    {
        $roles = [];
        foreach ([self::SUPERADMIN(), self::OPERATOR_SOAL(), self::OPERATOR_UJIAN()] as $item) {
            $roles[$item] = match ($item) {
                self::SUPERADMIN() => 'Superadmin',
                self::OPERATOR_UJIAN() => 'Operator Ujian',
                self::OPERATOR_SOAL() => 'Operator Soal',
            };
        }

        return $roles;
    }

    public static function getList()
    {
        $roles = [];
        foreach (self::names() as $item) {
            $roles[$item] = match ($item) {
                self::SUPERADMIN() => 'Superadmin',
                self::OPERATOR_UJIAN() => 'Operator Ujian',
                self::OPERATOR_SOAL() => 'Operator Soal',
                self::PARTICIPANT() => 'Peserta',
            };
        }

        return $roles;
    }
}
