<?php

use Step\Acceptance\ProductNavigation;
use Step\Acceptance\Basket;

class ProductDetailsPageCest
{
    /**
     * @group product
     *
     * @param AcceptanceTester $I
     */
    public function euroSignInTitle(AcceptanceTester $I)
    {
        $I->wantToTest('euro sign in the product title');

        //Add euro sign to the product title
        $I->updateInDatabase('oxarticles', ["OXTITLE" => '[DE 2] Test product 2 šÄßüл €'], ["OXID" => 1000]);

        $productData = [
            'id' => 1000,
            'title' => '[DE 2] Test product 2 šÄßüл €',
            'desc' => 'Test product 0 short desc [DE]',
            'price' => '50,00 € *'
        ];

        $searchListPage = $I->openShop()
            ->searchFor($productData['id'])
            ->switchLanguage('Deutsch');

        $searchListPage->seeProductData($productData, 1);

        $searchListPage->switchLanguage('English');

        //Remove euro sign from the product title
        $I->updateInDatabase('oxarticles', ["OXTITLE" => '[DE 2] Test product 2 šÄßüл'], ["OXID" => 1000]);
    }

    /**
     * @group product
     *
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
     * @group product
     *
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
     * @group product
     *
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
     * @group product
     *
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function productAccessories(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('Product\'s accessories');

        $data = [
            'OXID' => 'testaccessories1',
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
        $I->deleteFromDatabase('oxaccessoire2article', ['OXID' => 'testaccessories1']);
    }

    /**
     * @group product
     *
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function similarProducts(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('similar products');

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $similarProductData = [
            'id' => 1001,
            'title' => 'Test product 1 [EN] šÄßüл',
            'desc' => 'Test product 1 short desc [EN] šÄßüл',
            'price' => '100,00 € *'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $I->see($I->translate('SIMILAR_PRODUCTS'));
        $detailsPage->seeSimilarProductData($similarProductData, 1);
        $accessoryDetailsPage = $detailsPage->openSimilarProductDetailsPage(1);
        $accessoryDetailsPage->seeProductData($similarProductData);
        $detailsPage->seeSimilarProductData($productData, 1);
    }

    /**
     * @group product
     *
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function productCrossSelling(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('Product\'s crossselling');

        $data = [
            'OXID' => 'testcrossselling1',
            'OXOBJECTID' => '1002',
            'OXARTICLENID' => '1000',
        ];
        $I->haveInDatabase('oxobject2article', $data);

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $crossSellingProductData = [
            'id' => 1002,
            'title' => 'Test product 2 [EN] šÄßüл',
            'desc' => 'Test product 2 short desc [EN] šÄßüл',
            'price' => 'from 55,00 €'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $I->see($I->translate('HAVE_YOU_SEEN'));
        $detailsPage->seeCrossSellingData($crossSellingProductData, 1);
        $accessoryDetailsPage = $detailsPage->openCrossSellingDetailsPage(1);
        $accessoryDetailsPage->seeProductData($crossSellingProductData);

        $I->deleteFromDatabase('oxobject2article', ['OXID' => 'testcrossselling1']);
    }

    /**
     * @group main
     *
     * @param ProductNavigation $productNavigation
     */
    public function multidimensionalVariantsInDetailsPage(ProductNavigation $productNavigation)
    {
        $productNavigation->wantToTest('multidimensional variants functionality in details page');

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 € *'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);

        //assert product
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsNotBuyable();

        //select a variant of the product
        $detailsPage->selectVariant(1, 'S')
            ->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(2, 'black')
            ->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(3, 'lether');

        //assert product
        $productData = [
            'id' => '10014-1-1',
            'title' => '14 EN product šÄßüл S | black | lether',
            'desc' => '',
            'price' => '25,00 € *'
        ];
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsBuyable();

        //select a variant of the product
        $detailsPage = $detailsPage->selectVariant(2, 'white')
            ->checkIfProductIsNotBuyable();

        $detailsPage = $detailsPage->selectVariant(1, 'S');

        //assert product
        $productData = [
            'id' => '10014-1-3',
            'title' => '14 EN product šÄßüл S | white',
            'desc' => '',
            'price' => '15,00 € *'
        ];
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsBuyable();

        $detailsPage->selectVariant(2, 'black')
            ->selectVariant(3, 'lether')
            ->selectVariant(1, 'L');

        //assert product
        $productData = [
            'id' => '10014-3-1',
            'title' => '14 EN product šÄßüл L | black | lether',
            'desc' => '',
            'price' => '15,00 € *'
        ];
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsBuyable();

        $detailsPage = $detailsPage->addProductToBasket(2);

        //assert product in basket
        $basketItem = [
            'title' => '14 EN product šÄßüл, L | black | lether',
            'price' => '30,00 €',
            'amount' => 2
        ];
        $detailsPage->seeMiniBasketContains([$basketItem], '30,00 €', 2);
    }

    /**
     * @group product
     *
     * @param AcceptanceTester $I
     */
    public function multidimensionalVariantsInLists(AcceptanceTester $I)
    {
        $I->wantToTest('multidimensional variants functionality in lists');

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 €'
        ];

        $searchListPage = $I->openShop()
            ->searchFor($productData['id']);

        $searchListPage->seeProductData($productData, 1);

        $detailsPage = $searchListPage->selectVariant(1, 'M');
        $detailsPage->seeProductData($productData);
    }

    /**
     * @group main
     *
     * @param AcceptanceTester $I
     * @param ProductNavigation $productNavigation
     */
    public function multidimensionalVariantsAndJavaScript(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $productNavigation->wantToTest('if after md variants selection in details page all other js are still working correctly');

        $data = [
            'OXID' => '1001411',
            'OXLONGDESC' => 'Test description',
            'OXLONGDESC_1' => 'Test description',
        ];
        $I->haveInDatabase('oxartextends', $data);

        $data = [
            'OXID' => 'testattributes1',
            'OXOBJECTID' => '1001411',
            'OXATTRID' => 'testattribute1',
            'OXVALUE' => 'attr value 1 [DE]',
            'OXVALUE_1' => 'attr value 1 [EN]',
        ];
        $I->haveInDatabase('oxobject2attribute', $data);

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 € *'
        ];

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);

        //assert product
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsNotBuyable();

        //select a variant of the product
        $detailsPage->selectVariant(1, 'S')
            ->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(2, 'black')
            ->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(3, 'lether');

        //assert product
        $productData = [
            'id' => '10014-1-1',
            'title' => '14 EN product šÄßüл S | black | lether',
            'desc' => '',
            'price' => '25,00 € *'
        ];
        $detailsPage->seeProductData($productData)
            ->checkIfProductIsBuyable();

        $detailsPage = $detailsPage->openPriceAlert()
            ->openAttributes();

        $I->see('attr value 1 [EN]');

        $detailsPage = $detailsPage->openDescription();

        $I->see('Test description');

        $detailsPage = $detailsPage->addProductToBasket(2);

        //assert product in basket
        $basketItem = [
            'title' => '14 EN product šÄßüл, S | black | lether',
            'price' => '50,00 €',
            'amount' => 2
        ];
        $detailsPage->seeMiniBasketContains([$basketItem], '50,00 €', 2);

        $I->deleteFromDatabase('oxartextends', ["OXID" => '1001411']);
        $I->deleteFromDatabase('oxobject2attribute', ["OXID" => 'testattributes1']);
    }

    /**
     * @group product
     *
     * @param AcceptanceTester $I
     */
    public function multidimensionalVariantsAreOff(AcceptanceTester $I)
    {
        $I->wantToTest('multidimensional variants functionality is disabled');

        //multidimensional variants off
        $I->updateInDatabase('oxconfig', ["OXVARVALUE" => ''], ["OXVARNAME" => 'blUseMultidimensionVariants']);
        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 €'
        ];

        $searchListPage = $I->openShop()
            ->searchFor($productData['id']);

        $searchListPage->seeProductData($productData, 1);

        $detailsPage = $searchListPage->selectVariant(1, 'S | black | material');

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => '15,00 €'
        ];

        $detailsPage->seeProductData($productData)
            ->dontSeeVariant(1, 'M | black | lether')  //10014-2-1: out of stock - offline
            ->seeVariant(1, 'M | black | material');   //10014-2-2: out of stock - not orderable

        //making 10014-2-1 and 10014-2-2 variants in stock
        $I->updateInDatabase('oxarticles', ["OXSTOCK" => 1], ["OXID" => '1001421']);
        $I->updateInDatabase('oxarticles', ["OXSTOCK" => 1], ["OXID" => '1001422']);

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл S | white',
            'desc' => '13 EN description šÄßüл',
            'price' => '15,00 €'
        ];

        $detailsPage->selectVariant(1, 'S | white')->seeProductData($productData)
            ->seeVariant(1, 'M | black | lether')
            ->seeVariant(1, 'M | black | material');

        //roll back data
        $I->updateInDatabase('oxarticles', ["OXSTOCK" => 0], ["OXID" => '1001421']);
        $I->updateInDatabase('oxarticles', ["OXSTOCK" => 0], ["OXID" => '1001422']);
        //multidimensional variants on
        $I->cleanUp();
    }

    /**
     * @group product
     *
     * @param AcceptanceTester $I
     * @param Basket           $basket
     */
    public function bundledProduct(AcceptanceTester $I, Basket $basket)
    {
        $I->wantToTest('bundled product');

        $I->updateInDatabase('oxarticles', ["OXBUNDLEID" => '1001'], ["OXID" => '1000']);

        //add Product to basket
        /** @var \Page\Basket $basketPage */
        $basketPage = $basket->addProductToBasket('1000', 1, 'basket');

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'amount' => 1
        ];

        $bundledProductData = [
            'id' => 1001,
            'title' => 'Test product 1 [EN] šÄßüл',
            'amount' => '+1'
        ];

        $basketPage->seeBasketContains([$productData], '50,00 €')
            ->seeBasketContainsBundledProduct($bundledProductData, 2);

        $I->updateInDatabase('oxarticles', ["OXBUNDLEID" => ''], ["OXID" => '1000']);
    }

}
