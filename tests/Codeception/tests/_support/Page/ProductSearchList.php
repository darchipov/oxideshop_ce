<?php
namespace Page;

use Page\Header\LanguageMenu;

class ProductSearchList extends Page
{
    use LanguageMenu;

    // include url of current page
    public static $URL = '';

    public static $listItemTitle = '#searchList_%s';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.'/index.php?'.http_build_query(['cl' => 'search', 'searchparam' => $param]);
    }

    /**
     * @param int $itemId The position of the item in the list.
     *
     * @return string
     */
    public function getItemTitle($itemId)
    {
        return sprintf(self::$listItemTitle, $itemId);
    }
}
