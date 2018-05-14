<?php
namespace Page;

class Basket extends Page
{
    // include url of current page
    public static $URL = '';

    public static $basketSummaryField = '#basketGrandTotal';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($params)
    {
        return static::$URL.'/index.php?'.http_build_query($params);
    }

    public static function getBasketProductAmountField($id)
    {
        return '#am_'.$id;
    }
}
