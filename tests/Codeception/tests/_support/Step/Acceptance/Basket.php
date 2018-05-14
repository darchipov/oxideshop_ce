<?php
namespace Step\Acceptance;

use Page\Header\MiniBasket;
use Page\UserCheckout;

class Basket extends \AcceptanceTester
{

    public function openBasket()
    {
        $I = $this;
        $I->click(MiniBasket::$miniBasketMenuElement);
        $I->click($I->translate('DISPLAY_BASKET'));
    }

    /**
     * Assert basket product
     *
     * $basketProduct = ['id' => productId,
     *                   'title' => productTitle,
     *                   'amount' => productAmount,]
     *
     * @param array $basketProduct
     * @param string $basketSummaryPrice
     */
    public function seeBasketContains($basketProduct, $basketSummaryPrice)
    {
        $I = $this;
        $I->see($I->translate('PRODUCT_NO') . ' ' . $basketProduct['id']);
        $I->see($basketProduct['title']);
        $I->seeInField(\Page\Basket::getBasketProductAmountField(1), $basketProduct['amount']);
        $I->see($basketSummaryPrice, \Page\Basket::$basketSummaryField);
    }

    /**
     * @param $productId
     * @param $amount
     * @param $controller
     * @return UserCheckout
     */
    public function addProductToBasket($productId, $amount, $controller)
    {
        $I = $this;
        //add Product to basket
        $params['cl'] = $controller;
        $params['fnc'] = 'tobasket';
        $params['aid'] = $productId;
        $params['am'] = $amount;
        $params['anid'] = $productId;
        $I->amOnPage(\Page\Basket::route($params));
        $breadCrumbName = $I->translate("YOU_ARE_HERE").':'.$I->translate("ADDRESS");
        $I->see($breadCrumbName, UserCheckout::$breadCrumb);
        return new UserCheckout($I);
    }
}