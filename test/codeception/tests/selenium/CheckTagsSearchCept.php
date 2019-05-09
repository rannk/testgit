<?php
/*
 * @test_cover GLOW
 * in this test, just click the first tag in the sidebar and see if the result will be changed
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('verify the Tags search is work');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $url = "https://glow.popsugar.com/";
}else {
    $url = "https://dev.glow.popsugar.com/";
}

$I->amOnUrl($url);
$I->waitForElement(".navbar", 20);
$results_of_tags  = $I->grabTextFrom("#tags li:nth-child(1) .badge");
$name_of_tags = $I->grabTextFrom("#tags li:nth-child(1) .fancy-checkbox");
$name_of_tags_arr = explode("\n", $name_of_tags);
$name_of_tags = $name_of_tags_arr[0];
$I->click("#tags li:nth-child(1) .fancy-checkbox");
$I->waitForText($results_of_tags, 10, "#stats"); // check the result is same as tag's display
$I->seeInCurrentUrl($name_of_tags);
