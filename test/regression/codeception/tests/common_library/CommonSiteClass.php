<?php
class CommonSiteClass {

    public static $devBox;
    public static $current_url;

    public static function gotoHomepage(SeleniumGuy $I)
    {
        $devbox = CommonSiteClass::$devBox;

        if($devbox == 'prod') {
            $I->amOnUrl("https://sparkle.popsugar.com");
        }else {
            $I->amOnUrl("https://sparkle-dev.popsugar.com");
        }
    }

    public static function signIn(SeleniumGuy $I, $username = "", $password = "")
    {
        $I->amOnPage("/sign-in");
        $I->waitForText("Sign In", 10);
        $I->fillField("input[name='username']", $username);
        $I->fillField("input[name='password']", $password);
        $I->click("Log in");
        $I->waitForText("My Dashboard", 10, "#globalNavbar");
    }
}

