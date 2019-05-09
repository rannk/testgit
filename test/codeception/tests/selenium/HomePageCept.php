<?php
/*
 * @test_cover GLOW
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the homepage of glow site');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $url = "https://glow.popsugar.com/";
}else {
    $url = "https://dev.glow.popsugar.com/";
}

$I->amOnUrl($url);
// check navgation
$I->waitForElement(".navbar", 20);
$I->seeElement(".navbar-brand");
$I->seeNumberOfElements(".navbar-nav .nav-item", [6, 8]);
$I->seeElement(".navbar-nav .cart");
$I->seeElement(".navbar #aa-search-input");
// check carousel
$I->seeElement("#myCarousel .carousel-item");
// check sidebar
$I->seeElement("#tags .ais-SearchBox-input");
$I->seeNumberOfElements("#tags .ais-RefinementList-item", [2,6]);
$I->seeNumberOfElements("#difficulty .ais-RefinementList-item", [2,6]);
$I->seeElement("#price .ais-RangeSlider");
$I->seeNumberOfElements("#duration .ais-NumericMenu-item", [2, 6]);
$I->seeNumberOfElements("#content-types .ais-RefinementList-item", [2,6]);
$I->seeElement("#shops .ais-SearchBox-input");
$I->seeNumberOfElements("#shops .ais-RefinementList-item", [2,12]);
$I->seeElement("#clear-refinements"); // button
// check results section
$I->seeElement("#sort-by"); // sort selection
// check the first result
$I->seeElement("#hits .ais-Hits-item:nth-child(1) .avatar-item");
$I->seeElement("#hits .ais-Hits-item:nth-child(1) .avatar-item .duration");
$I->seeNumberOfElements("#hits .ais-Hits-item:nth-child(1) .px-sm-2", [3, 4]); // have 3-4 lines under the avatar-item (sponser, title, price, etc)
// check footer
$I->see("Glow by POPSUGAR", "footer");
$I->see("Sell on Glow", "footer .legal");
$I->see("FAQ", "footer .legal");
$I->see("Contact", "footer .legal");
$I->see("Terms", "footer .legal");
$I->see("Privacy", "footer .legal");
$I->seeElement("footer .icon-instagram");
$I->seeElement("footer .icon-facebook");
$I->seeElement("footer .icon-twitter");
