<?php
/*
 * @test_cover GLOW
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the product detail page can be displayed as normal');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $url = "https://glow.popsugar.com/";
}else {
    $url = "https://dev.glow.popsugar.com/";
}

$I->amOnUrl($url);
$I->waitForElement(".navbar", 20);
// check detail
$I->click("#hits .ais-Hits-item:nth-child(1) .avatar-item");
$I->waitForElement(".product-page", 10);
$I->seeElement(".teaser"); // the intro section
$I->seeNumberOfElements(".payment-container .btn", 2); // payment button and add to cart button
$I->seeNumberOfElements(".item-cell", [1, 150]); // include in this ... (items)
$I->see("Reviews", ".h4");
// check footer
$I->see("Glow by POPSUGAR", "footer");
$I->see("Sell on Glow", "footer .legal");
$I->see("FAQ", "footer .legal");
$I->see("Customer Service", "footer .legal");
$I->see("Terms", "footer .legal");
$I->see("Privacy", "footer .legal");
$I->seeElement("footer .icon-instagram");
$I->seeElement("footer .icon-facebook");
$I->seeElement("footer .icon-twitter");
