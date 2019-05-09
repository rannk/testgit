<?php
/*
 * @test_cover GLOW
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the new user can buy the item');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $I->comment("this test only run on devbox");
    return;
}

$username = "newuser_" . time();

$I->amOnUrl("https://dev.glow.popsugar.com/@popsugar/plan/30-days-to-a-flat-belly-QkCza7HNMF");
$I->waitForElement(".payment-container a[href*='checkout']", 20);
$I->click(".payment-container a[href*='checkout']");

// create new account
$I->waitForText('Username', 30);
$I->fillField("input[name='username']", $username);
$I->fillField("input[name='name']", $username);
$I->fillField("input[name='email']", $username . "@popsugar.com");
$I->fillField("input[name='password']", "Popsugar25");
$I->fillField("input[name='phoneNumber']", "55555555");
$I->click(".btn-lg");
// fill the billing info
$I->waitForText("Pay with card", 20);
$I->seeElement(".checkout .order-1 .avatar-item"); // order info
$I->fillField("#checkout-name", $username);
$I->fillField("#checkout-street", "111 Sutter Street, Suite 850");
$I->fillField("#checkout-city", "San Francisco");
$I->fillField("#checkout-state", "CA");
$I->fillField("#checkout-postalCode", "94104");
//$I->selectOption("#checkout-country-selector", "US");
$I->see('$9.99', "#subtotal-value"); // order summary
$I->see('$9.99', "#total-container");
$card_iframe_name = $I->grabAttributeFrom("#checkout-card iframe", "name");
$I->switchToIFrame($card_iframe_name);
$I->waitForElement("input[name='cardnumber']", 5);
$I->fillField("input[name='cardnumber']", "4242424242424242");
$I->fillField("input[name='exp-date']", "11/25");
$I->fillField("input[name='cvc']", "111");
$I->switchToIFrame();
$I->wait(1);
$I->click("#pay-button");
$I->waitForText("Payment successful", 20);
// check purchased item
$I->click("View Content", ".checkout-success");
$I->waitForText("My Library", 20, ".h2");
$I->seeElement(".my-purchases .pb-4");
