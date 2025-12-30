<?php

namespace App\Common;

class CommonPublicView
{

    public static function getShowProductView(): string
    {
        return self::baseView().'home.product.show';
    }
    public static function getAboutView(): string
    {
        return self::baseView().'about.about';
    }
    public static function getHomeView(): string
    {
        return self::baseView().'home.home';
    }

    public static function getContactView(): string
    {
        return self::baseView().'contact.contact';
    }
    private static function baseView(): string
    {
        return "public.";
    }
}