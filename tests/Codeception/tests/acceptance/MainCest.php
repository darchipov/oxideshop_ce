<?php

use Page\UserRegistration;
use Page\Home;
use Page\UserOrderHistory;

class MainCest
{
    public function frontPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(\Page\Home::$URL);
        $I->see($I->translate("HOME"));
    }

  /*  public function frontendMultidimensionalVariantsOnDetailsPage(\Step\Acceptance\ProductDetails $I, \Step\Acceptance\Basket $basket, \Step\Acceptance\ProductNavigation $prodNav)
    {
        $I->updateInDatabase('oxarticles', ["OXACTIVE" => 1], ["OXID" => 10014]);

        $productData = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 € *'
        ];
        $productVariants = ['size[EN]:', 'color:', 'type:'];
        //open details page
        $detailsPage = $prodNav->openProductDetailsPage($productData);

        //assert product
        $this->seeProduct($I, $productData, $I->getProductInformation());

        $I->checkIfProductIsNotBuyable();

        //select a variant of the product
        $detailsPage->selectVariant(1, 'S');
        $I->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(2, 'black');
        $I->checkIfProductIsNotBuyable();
        $detailsPage->selectVariant(3, 'lether');

        //assert product
        $productData = [
            'id' => '10014-1-1',
            'title' => '14 EN product šÄßüл S | black | lether',
            'desc' => '',
            'price' => '25,00 € *'
        ];
        $I->seeProduct($productData);
        $I->checkIfProductIsBuyable();

        //select a variant of the product
        $detailsPage->selectVariant(2, 'white');
        $I->checkIfProductIsNotBuyable();

        $detailsPage->selectVariant(1, 'S');

        //assert product
        $productData = [
            'id' => '10014-1-3',
            'title' => '14 EN product šÄßüл S | white',
            'desc' => '',
            'price' => '15,00 € *'
        ];
        $I->seeProduct($productData);
        $I->checkIfProductIsBuyable();

        $detailsPage->selectVariant(2, 'black');
        $detailsPage->selectVariant(3, 'lether');
        $detailsPage->selectVariant(1, 'L');

        //assert product
        $productData = [
            'id' => '10014-3-1',
            'title' => '14 EN product šÄßüл L | black | lether',
            'desc' => '',
            'price' => '15,00 € *'
        ];
        $I->seeProduct($productData);
        $I->checkIfProductIsBuyable();

        $I->addProductToBasket(2);

        $basket->openBasket();

        //assert product
        $basketData = [
            'id' => '10014-3-1',
            'title' => '14 EN product šÄßüл, L | black | lether',
            'amount' => 2,
        ];
        $basket->seeBasketContains($basketData, '30,00 €');
    }

    /**
     * Assert active product.
     *
     * $productData = ['id' => productId,
     *                 'title' => productTitle,
     *                 'desc' => productShortDesc,
     *                 'price' => productPrice]
     *
     * @param AcceptanceTester $I
     * @param array $productData
     * @param array $actualProductData
     */
    private function seeProduct(AcceptanceTester $I, array $productData, $actualProductData)
    {
        foreach ($productData as $key => $value)
        {
            $I->see($value, $actualProductData[$key]);
        }
    }

    public function shopBrowsing(AcceptanceTester $I)
    {
        // open start page
        $I->amOnPage(\Page\Home::$URL);

        $I->see($I->translate("HOME"));
        $I->see($I->translate('START_BARGAIN_HEADER'));

        // open category
        $I->click('Test category 0 [EN] šÄßüл', '#navigation');
        $I->see('Test category 0 [EN] šÄßüл', 'h1');

        // check if subcategory exists
        $I->see('Test category 1 [EN] šÄßüл', '#moreSubCat_1');

        //open Details page
        $I->click('#productList_1');

        // login to shop
        $I->amOnPage(UserOrderHistory::$URL);
        $I->see($I->translate('LOGIN'), 'h1');

        $I->fillField(UserOrderHistory::$loginUserNameField,'example_test@oxid-esales.dev');
        $I->fillField(UserOrderHistory::$loginUserPasswordField,'useruser');
        $I->click(UserOrderHistory::$loginButton);

        $I->see($I->translate('ORDER_HISTORY'), 'h1');
    }

}
