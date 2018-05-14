<?php
namespace Page\Header;

use Page\Home;

trait Navigation
{
    public static $homeLink = '//ul[@id="navigation"]/li[1]/a';

    /**
     * @return Home
     */
    public function openHomePage()
    {
        /** @var \AcceptanceTester $I */
        $I = $this->user;
        $I->click(self::$homeLink);
        return new Home($I);
    }
}
