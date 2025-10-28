<?php

namespace App\Common;

class PermissionView
{
    public static function getPermissionListView(): string
    {
        return self::baseView().'list';
    }

    public static function getPermissionCreateOrEditView(): string
    {
        return self::baseView().'action.createOrEdit';
    }

    public static function getPermissionShowView(): string
    {
        return self::baseView().'action.show';
    }

    public static function getPermissionDriveView(): string
    {
        return self::baseView().'drive';
    }
    private static function baseView(): string
    {
        return "admin.access.permission.";
    }
}