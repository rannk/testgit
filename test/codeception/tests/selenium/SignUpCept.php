<?php
/*
 * @test_cover GLOW
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the sign up/sign out function is work');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $I->comment("this test only run on devbox");
    return;
}

$username = "qa_test_" . time();

$I->amOnUrl("https://dev.glow.popsugar.com/sign-up");
// create new account
$I->waitForText('Create an Account', 30);
$I->fillField("input[name='username']", $username);
$I->fillField("input[name='name']", $username);
$I->fillField("input[name='email']", $username . "@popsugar.com");
$I->fillField("input[name='password']", "Popsugar25");
$I->fillField("input[name='phoneNumber']", "55555555");
$I->click(".btn-lg");
// The account was created
$I->waitForElement("#navbarDropdown", 20);
// check account menu and sign out
$I->click("#navbarDropdown");
$I->waitForElementVisible(".dropdown-menu", 5);
$I->see("Profile", ".dropdown-menu");
$I->see("My Library", ".dropdown-menu");
$I->see("My Purchases", ".dropdown-menu");
$I->see("Messages", ".dropdown-menu");
$I->see("Settings", ".dropdown-menu");
$I->see("Sign Out", ".dropdown-menu");
$I->click("Sign Out", ".dropdown-menu");
$I->waitForElementNotVisible("#navbarDropdown", 10);
