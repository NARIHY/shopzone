<?php

namespace App\Common;

class MediaView
{
    public static function getMediaListView(): string
    {
        return self::baseView().'list';
    }

    public static function getMediaCreateOrEditView(): string
    {
        return self::baseView().'action.createOrEdit';
    }

    public static function getMediaShowView(): string
    {
        return self::baseView().'action.show';
    }

    private static function baseView(): string
    {
        return "admin.media.";
    }
}