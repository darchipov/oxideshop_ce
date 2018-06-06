<?php

use Step\Acceptance\ProductNavigation;

class ProductDetailsPageCest
{
    public function euroSignInTitle(AcceptanceTester $I)
    {
        $I->wantToTest('euro sign in the product title');

        //Add euro sign to the product title
        $I->updateInDatabase('oxarticles', ["OXTITLE" => '[DE 2] Test product 2 šÄßüл €'], ["OXID" => 1000]);

        $searchListPage = $I->openShop()
            ->searchFor('1000')
            ->switchLanguage('Deutsch');

        $I->see('[DE 2] Test product 2 šÄßüл €', $searchListPage->getItemTitle(1));

        $searchListPage->switchLanguage('English');

        //Remove euro sign from the product title
        $I->updateInDatabase('oxarticles', ["OXTITLE" => '[DE 2] Test product 2 šÄßüл'], ["OXID" => 1000]);
    }

    /**
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function sendProductSuggestionEmail(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantTo('send the product suggestion email');

        //(Use gift registry) is disabled
        $I->updateInDatabase('oxconfig', ["OXVARVALUE" => ''], ["OXVARNAME" => 'iUseGDVersion']);

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];
        $emptyEmailData = [
            'recipient_name' => '',
            'recipient_email' => '',
            'sender_name' => '',
            'sender_email' => '',
        ];
        $suggestionEmailData = [
            'recipient_name' => 'Test User',
            'recipient_email' => 'example@oxid-esales.dev',
            'sender_name' => 'user',
            'sender_email' => 'example_test@oxid-esales.dev',
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $suggestionPage = $detailsPage->openProductSuggestionPage()->sendSuggestionEmail($emptyEmailData);
        $I->see($I->translate('ERROR_MESSAGE_INPUT_NOTALLFIELDS'));
        $suggestionPage->sendSuggestionEmail($suggestionEmailData);
        $I->see($productData['title']);

        //(Use gift registry) is enabled
        $I->cleanUp();
    }

    /**
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function productPriceAlert(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('product price alert functionality');

        //(Use gift registry) is disabled
        $I->updateInDatabase('oxconfig', ["OXVARVALUE" => ''], ["OXVARNAME" => 'iUseGDVersion']);

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);
        $I->see($I->translate('PRICE_ALERT'));

        $detailsPage->sendPriceAlert('example_test@oxid-esales.dev', '99.99');
        $I->see($I->translate('PAGE_DETAILS_THANKYOUMESSAGE3').' 99,99 € '.$I->translate('PAGE_DETAILS_THANKYOUMESSAGE4'));
        $I->see($productData['title']);

        //disabling price alert for product(1000)
        $I->updateInDatabase('oxarticles', ["oxblfixedprice" => 1], ["OXID" => 1000]);

        //open details page
        $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);
        $I->dontSee($I->translate('PRICE_ALERT'));

        //(Use gift registry) is enabled
        $I->cleanUp();
    }

    /**
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function productVariantSelection(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('product variant selection in details page');

        $productData = [
            'id' => 1002,
            'title' => 'Test product 2 [EN] šÄßüл',
            'desc' => 'Test product 2 short desc [EN] šÄßüл',
            'price' => 'from 55,00 € *'
        ];

        $variantData1 = [
            'id' => '1002-1',
            'title' => 'Test product 2 [EN] šÄßüл var1 [EN] šÄßüл',
            'desc' => '',
            'price' => '55,00 € *'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);
        $detailsPage->seeProductData($productData);

        // select variant
        $detailsPage = $detailsPage->selectVariant(1, 'var1 [EN] šÄßüл')
            ->seeProductData($variantData1);

        $basketItem1 = [
            'title' => 'Test product 2 [EN] šÄßüл, var1 [EN] šÄßüл',
            'price' => '110,00 €',
            'amount' => 2
        ];
        $detailsPage = $detailsPage->addProductToBasket(2)
            ->seeMiniBasketContains([$basketItem1], '110,00 €', 2);

        $basketItem1 = [
            'title' => 'Test product 2 [EN] šÄßüл, var1 [EN] šÄßüл',
            'price' => '165,00 €',
            'amount' => 3
        ];
        $detailsPage = $detailsPage->addProductToBasket(1)
            ->seeMiniBasketContains([$basketItem1], '165,00 €', 3);

        // select second variant
        $variantData2 = [
            'id' => '1002-2',
            'title' => 'Test product 2 [EN] šÄßüл var2 [EN] šÄßüл',
            'desc' => '',
            'price' => '67,00 € *'
        ];

        $detailsPage = $detailsPage->selectVariant(1, 'var2 [EN] šÄßüл')
            ->seeProductData($variantData2);

        $basketItem2 = [
            'title' => 'Test product 2 [EN] šÄßüл, var2 [EN] šÄßüл',
            'price' => '201,00 €',
            'amount' => 3
        ];
        $detailsPage->addProductToBasket(2)
            ->addProductToBasket(1)
            ->seeMiniBasketContains([$basketItem1, $basketItem2], '366,00 €', 6);

        $I->deleteFromDatabase('oxuserbaskets', ['oxuserid' => 'testuser']);
        $I->clearShopCache();
    }

    /**
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function productAccessories(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('Product\'s accessories');

        $data = [
            'OXID' => 'e2647c561ffb990a8.18051802',
            'OXOBJECTID' => '1002',
            'OXARTICLENID' => '1000',
        ];
        $I->haveInDatabase('oxaccessoire2article', $data);

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $accessoryData = [
            'id' => 1002,
            'title' => 'Test product 2 [EN] šÄßüл',
            'desc' => 'Test product 2 short desc [EN] šÄßüл',
            'price' => 'from 55,00 €'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $I->see($I->translate('ACCESSORIES'));
        $detailsPage->seeAccessoryData($accessoryData, 1);
        $accessoryDetailsPage = $detailsPage->openAccessoryDetailsPage(1);
        $accessoryDetailsPage->seeProductData($accessoryData);
    }

}
