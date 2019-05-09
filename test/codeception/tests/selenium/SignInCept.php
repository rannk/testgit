<?php
/*
 * @test_cover GLOW
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the sign in function is work');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $url = "https://glow.popsugar.com/sign-in";
}else {
    $url = "https://dev.glow.popsugar.com/sign-in";
}

$username = "popsugar";
$password = "Sug@r925";

$I->amOnUrl($url);
// create new account
$I->waitForText('Sign In', 30);
$I->fillField("input[name='username']", $username);
$I->fillField("input[name='password']", $password);
$I->click(".btn-lg");
// The account was created
$I->waitForElement("#navbarDropdown", 20);
