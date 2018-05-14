<?php
namespace Step\Acceptance;

use Page\ProductDetails as ProductDetailsObject;

class ProductDetails extends \AcceptanceTester
{

    /**
     * Open product details page.
     *
     * @param string $productId The Id of the product
     */
    public function openProductDetailsPage($productId)
    {
        $I = $this;

        $I->amOnPage(ProductDetailsObject::route($productId));
        return new ProductDetailsObject($I);
    }

    /**
     * Return information of the active product.
     *
     * $productData = ['id' => productIdElement,
     *                 'title' => productTitleElement,
     *                 'desc' => productShortDescElement,
     *                 'price' => productPriceElement]
     *
     * @return array $productData
     */
    public function getProductInformation()
    {
        $productData['title'] = ProductDetailsObject::$productTitle;
        $productData['desc'] = ProductDetailsObject::$productShortDesc;
        $productData['id'] = null;
        $productData['price'] = ProductDetailsObject::$productPrice;
        return $productData;
    }

    public function selectVariant($variant, $variantValue, $waitForText = null)
    {
        $I = $this;

        $I->click(ProductDetailsObject::getVariantSelect($variant));
        $I->click($variantValue);
        //wait for JS to finish
        $I->waitForJS("return $.active == 0;",10);
    }

    /**
     * Assert active product.
     *
     * $productData = ['id' => productId,
     *                 'title' => productTitle,
     *                 'desc' => productShortDesc,
     *                 'price' => productPrice]
     *
     * @param array $productData
     */
    public function seeProduct(array $productData)
    {
        $I = $this;
        $I->see($productData['title'], ProductDetailsObject::$productTitle);
        $I->see($productData['desc'], ProductDetailsObject::$productShortDesc);
        $I->see($productData['id']);
        $I->see($productData['price'], ProductDetailsObject::$productPrice);

    }

    /**
     * Assert variants of the product.
     *
     * $productVariants = [variantName1, variantName2, ...]
     *
     * @param array $productVariants An array with a name list of variants
     */
    public function seeVariants($productVariants)
    {
        $I = $this;
        $I->see($I->translate('CHOOSE_VARIANT'));
        $variantInt = 1;
        foreach ($productVariants as $variantName) {
            $I->see($variantName, ProductDetailsObject::getVariantLabel($variantInt));
            $variantInt++;
        }
    }

    /**
     * Assert if user can buy current product
     */
    public function checkIfProductIsBuyable()
    {
        $I = $this;
        $I->dontSeeElement(ProductDetailsObject::getDisabledToBasketButton());
    }

    /**
     * Assert if user cannot buy current product
     */
    public function checkIfProductIsNotBuyable()
    {
        $I = $this;
        $I->seeElement(ProductDetailsObject::getDisabledToBasketButton());
    }

    /**
     * Add current product to basket
     *
     * @param int $amount
     */
    public function addProductToBasket($amount = 1)
    {
        $I = $this;
        $I->fillField(ProductDetailsObject::$basketAmountField, $amount);
        $I->click(ProductDetailsObject::$toBasketButton);
    }
}