<?php 

namespace App\Common;

class CategoryProductView
{
    public static function getCategoryListView(): string
    {
        return self::baseView().'list';
    }

    public static function getCategoryCreateOrEditView(): string
    {
        return self::baseView().'action.createOrEdit';
    }

    public static function getCategoryShowView(): string
    {
        return self::baseView().'action.show';
    }

    private static function baseView(): string
    {
        return "admin.shop.productCategory.";
    }
}