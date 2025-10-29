<?php 

namespace App\Common;

class RoleToPermissionView
{
    public static function getListRoleToPermissionView(): string
    {
        return self::baseView().'list';
    }

    public static function getCreateOrEditRoleToPermissionView(): string
    {
        return self::baseView().'action.createOrEdit';
    }

    public static function getShowRoleToPermissionView(): string
    {
        return self::baseView().'action.show';
    }

    private static function baseView(): string
    {
        return "admin.access.role.roleToPermission.";
    }
}