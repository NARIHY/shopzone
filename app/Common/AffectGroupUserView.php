<?php 

namespace App\Common;

class AffectGroupUserView
{
    public static function getListView(): string
    {
        return self::baseView().'list';
    }

    public static function getCreateOrEditView(): string
    {
        return self::baseView().'action.createOrEdit';
    }

    public static function getShowView(): string
    {
        return self::baseView().'action.show';
    }

    private static function baseView(): string
    {
        return "admin.access.affectGroupUser.";
    }
}