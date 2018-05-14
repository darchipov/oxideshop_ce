<?php
namespace Step\Acceptance;

use Page\Home;

class Start extends \AcceptanceTester
{
    public function registerUserForNewsletter($userEmail, $userName, $userLastName)
    {
        $I = $this;
        $homePage = new Home($I);
        $newsletterPage = $homePage->subscribeForNewsletter($userEmail);
        $newsletterPage->enterUserData($userEmail, $userName, $userLastName)->subscribe();
        $I->see($I->translate('MESSAGE_THANKYOU_FOR_SUBSCRIBING_NEWSLETTERS'));
        return $newsletterPage;

    }

    public function loginOnStartPage($userName, $userPassword)
    {
        $I = $this;
        $startPage = $I->openShop();
        // if snapshot exists - skipping login
        if ($I->loadSessionSnapshot('login')) {
            return $startPage;
        }
        $startPage = $startPage->loginUser($userName, $userPassword);
        $I->saveSessionSnapshot('login');
        return $startPage;
    }
}