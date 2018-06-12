<?php
namespace Page;

use Page\Account\UserLogin;
use Page\Header\AccountMenu;
use Page\Header\LanguageMenu;
use Page\Header\MiniBasket;

class ProductDetails extends Page
{
    use AccountMenu, LanguageMenu, MiniBasket;

    // include url of current page
    public static $URL = '';

    public static $productTitle = '#productTitle';

    public static $productShortDesc = '#productShortdesc';

    public static $productArtNum = '';

    public static $productPrice = '#productPrice';

    public static $toBasketButton = '#toBasket';

    public static $basketAmountField = '#amountToBasket';

    public static $addToCompareListLink = '#addToCompare';

    public static $addToWishListLink = '#linkToNoticeList';

    public static $addToGiftRegistryLink = '#linkToWishList';

    public static $reviewLoginLink = '#reviewsLogin';

    public static $openReviewForm = '#writeNewReview';

    public static $reviewTextForm = 'rvw_txt';

    public static $ratingSelection = '//ul[@id="reviewRating"]/li[%s]';

    public static $saveRatingAndReviewButton = '#reviewSave';

    public static $productReviewAuthor = '//div[@id="reviewName_%s"]/div[2]/div/div[1]/span[1]';

    public static $productReviewText = '#reviewText_%s';

    public static $userProductRating = '//div[@id="reviewName_%s"]/div[2]/div/div[2]/div[1]/i[@class="fa fa-star"]';

    public static $productSuggestionLink = '#suggest';

    public static $priceAlertEmail = 'pa[email]';

    public static $priceAlertSuggestedPrice = 'pa[price]';

    public static $accessoriesProductTitle = '#accessories_%s';

    public static $accessoriesProductPrice = '//form[@name="tobasketaccessories_%s"]/div/div[@class="price text-center"]';

    public static $similarProductTitle = '#similar_%s';

    public static $similarProductPrice = '//form[@name="tobasketsimilar_%s"]/div/div[@class="price text-center"]';

    public static $crossSellingProductTitle = '#cross_%s';

    public static $crossSellingProductPrice = '//form[@name="tobasketcross_%s"]/div/div[@class="price text-center"]';

    public static $disabledBasketButton = '//button[@id="toBasket" and @disabled="disabled"]';

    public static $variantSelection = '/descendant::button[@class="btn btn-default btn-sm dropdown-toggle"][%s]';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.'/index.php?'.http_build_query(['cl' => 'details', 'anid' => $param]);
    }

    /**
     * Assert if user cannot buy current product
     *
     * @return $this
     */
    public function checkIfProductIsNotBuyable()
    {
        $I = $this->user;
        $I->seeElement(self::$disabledBasketButton);
        return $this;
    }

    /**
     * Assert if user can buy current product
     *
     * @return $this
     */
    public function checkIfProductIsBuyable()
    {
        $I = $this->user;
        $I->dontSeeElement(self::$disabledBasketButton);
        return $this;
    }

    /**
     * @param int    $variant
     * @param string $variantValue
     * @param string $waitForText
     *
     * @return $this
     */
    public function selectVariant($variant, $variantValue, $waitForText = null)
    {
        $I = $this->user;
        $I->click(sprintf(self::$variantSelection, $variant));
        $I->click($variantValue);
        //wait for JS to finish
        $I->waitForJS("return $.active == 0;",10);
        return $this;
    }

    /**
     * @param int    $variant
     * @param string $variantValue
     *
     * @return $this
     */
    public function seeVariant($variant, $variantValue)
    {
        $I = $this->user;
        $I->click(sprintf(self::$variantSelection, $variant));
        $I->see($variantValue);
        $I->click(sprintf(self::$variantSelection, $variant));
        return $this;
    }

    /**
     * @param int    $variant
     * @param string $variantValue
     *
     * @return $this
     */
    public function dontSeeVariant($variant, $variantValue)
    {
        $I = $this->user;
        $I->click(sprintf(self::$variantSelection, $variant));
        $I->dontSee($variantValue);
        $I->click(sprintf(self::$variantSelection, $variant));
        return $this;
    }

    /**
     * @return $this
     */
    public function addToCompareList()
    {
        $I = $this->user;
        $I->click(self::$addToCompareListLink);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromCompareList()
    {
        $I = $this->user;
        //TODO: not like in azure
        $I->click(self::$addToCompareListLink);
        return $this;
    }

    /**
     * @return $this
     */
    public function addToWishList()
    {
        $I = $this->user;
        $I->click(self::$addToWishListLink);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromWishList()
    {
        $I = $this->user;
        $I->click(self::$addToWishListLink);
        return $this;
    }

    /**
     * @return $this
     */
    public function addToGiftRegistryList()
    {
        $I = $this->user;
        $I->click(self::$addToGiftRegistryLink);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromGiftRegistryList()
    {
        $I = $this->user;
        $I->click(self::$addToGiftRegistryLink);
        return $this;
    }

    /**
     * @param string $userName
     * @param string $userPassword
     *
     * @return $this
     */
    public function loginUserForReview($userName, $userPassword)
    {
        $I = $this->user;
        $I->click(self::$reviewLoginLink);
        $breadCrumb = $I->translate('YOU_ARE_HERE').':'.$I->translate('LOGIN');
        $I->see($breadCrumb, UserLogin::$breadCrumb);
        $userLoginPage = new UserLogin($I);
        $userLoginPage->login($userName, $userPassword);
        return $this;
    }

    /**
     * @param string $review
     * @param int    $rating
     *
     * @return $this
     */
    public function addReviewAndRating($review, $rating)
    {
        $I = $this->user;
        $I->click(self::$openReviewForm);
       // $I->waitForElement(self::$reviewTextForm);
        $I->fillField(self::$reviewTextForm, $review);
        $I->click(sprintf(self::$ratingSelection, $rating));
        $I->click(self::$saveRatingAndReviewButton);
        return $this;
    }

    /**
     * @param int    $reviewId The position of the review item.
     * @param string $userName
     * @param string $reviewText
     * @param int    $rating
     *
     * @return $this
     */
    public function seeUserProductReviewAndRating($reviewId, $userName, $reviewText, $rating)
    {
        $I = $this->user;
        $I->see($userName, sprintf(self::$productReviewAuthor, $reviewId));
        $I->see($reviewText, sprintf(self::$productReviewText, $reviewId));
        $I->seeNumberOfElements(sprintf(self::$userProductRating, $reviewId), $rating);
        return $this;
    }

    /**
     * Opens recommend page.
     *
     * @return ProductSuggestion
     */
    public function openProductSuggestionPage()
    {
        $I = $this->user;
        $I->click(self::$productSuggestionLink);
        $breadCrumb = $I->translate('YOU_ARE_HERE').':'.$I->translate('RECOMMEND_PRODUCT');
        $I->see($breadCrumb, ProductSuggestion::$breadCrumb);
        $I->see($I->translate('RECOMMEND_PRODUCT'), ProductSuggestion::$headerTitle);
        return new ProductSuggestion($I);
    }

    /**
     * @param string $email
     * @param double $price
     *
     * @return $this
     */
    public function sendPriceAlert($email, $price)
    {
        $I = $this->user;
        $this->openPriceAlert();
        $I->fillField(self::$priceAlertEmail, $email);
        $I->fillField(self::$priceAlertSuggestedPrice, $price);
        $I->click($I->translate('SEND'));
        return $this;
    }

    /**
     * @return $this
     */
    public function openPriceAlert()
    {
        $I = $this->user;
        $I->click($I->translate('PRICE_ALERT'));
        $I->see($I->translate('MESSAGE_PRICE_ALARM_PRICE_CHANGE'));
        return $this;
    }

    /**
     * @return $this
     */
    public function openAttributes()
    {
        $I = $this->user;
        $I->click($I->translate('SPECIFICATION'));
        return $this;
    }

    /**
     * @return $this
     */
    public function openDescription()
    {
        $I = $this->user;
        $I->click($I->translate('DESCRIPTION'));
        return $this;
    }

    /**
     * @param array $productData
     *
     * @return $this
     */
    public function seeProductData($productData)
    {
        $I = $this->user;
        $I->see($productData['title'], self::$productTitle);
        $I->see($productData['desc'], self::$productShortDesc);
        $I->see($productData['id']);
        $I->see($productData['price'], self::$productPrice);
        return $this;
    }

    /**
     * Add current product to basket
     *
     * @param int $amount
     *
     * @return $this
     */
    public function addProductToBasket($amount = 1)
    {
        $I = $this->user;
        $I->fillField(self::$basketAmountField, $amount);
        $I->click(self::$toBasketButton);
        return $this;
    }

    /**
     * @param array $productData
     * @param int   $position
     *
     * @return $this
     */
    public function seeAccessoryData($productData, $position = 1)
    {
        $I = $this->user;
        $I->see($productData['title'], sprintf(self::$accessoriesProductTitle, $position));
        $I->see($productData['price'], sprintf(self::$accessoriesProductPrice, $position));
        return $this;
    }

    /**
     * @param int   $position
     *
     * @return $this
     */
    public function openAccessoryDetailsPage($position = 1)
    {
        $I = $this->user;
        $I->click(sprintf(self::$accessoriesProductTitle, $position));
        return $this;
    }

    /**
     * @param array $productData
     * @param int   $position
     *
     * @return $this
     */
    public function seeSimilarProductData($productData, $position = 1)
    {
        $I = $this->user;
        $I->see($productData['title'], sprintf(self::$similarProductTitle, $position));
        $I->see($productData['price'], sprintf(self::$similarProductPrice, $position));
        return $this;
    }

    /**
     * @param int   $position
     *
     * @return $this
     */
    public function openSimilarProductDetailsPage($position = 1)
    {
        $I = $this->user;
        $I->click(sprintf(self::$similarProductTitle, $position));
        return $this;
    }

    /**
     * @param array $productData
     * @param int   $position
     *
     * @return $this
     */
    public function seeCrossSellingData($productData, $position = 1)
    {
        $I = $this->user;
        $I->see($productData['title'], sprintf(self::$crossSellingProductTitle, $position));
        $I->see($productData['price'], sprintf(self::$crossSellingProductPrice, $position));
        return $this;
    }

    /**
     * @param int   $position
     *
     * @return $this
     */
    public function openCrossSellingDetailsPage($position = 1)
    {
        $I = $this->user;
        $I->click(sprintf(self::$crossSellingProductTitle, $position));
        return $this;
    }

}
