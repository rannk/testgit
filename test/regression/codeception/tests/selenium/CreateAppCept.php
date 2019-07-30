<?php
/**
 * @author Rannk Deng
 */

$scenario->group('sparkle-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('create App for sparkle');

$devbox = CommonSiteClass::$devBox;

if($devbox == 'prod') {
    $I->expect("this test only run on devbox");
    return;
}

CommonSiteClass::gotoHomepage($I);
CommonSiteClass::signIn($I);

$title = "Copy of Email Capture " . time();

$I->amOnPage("/templates");
$I->waitForText("Email Capture", 20);
$I->click("Email Capture");
$I->waitForElement("input[name='name']", 20);
$I->executeJS('$("#app-edit").attr("check","true")'); // add a tag for this page, this is for checking page loading
$I->fillField("input[name='name']", $title);
$I->click("Save & Refresh");
$I->waitForElementNotVisible("#app-edit[check='true']", 10);
$I->amOnPage("/dashboard");
$I->waitForText($title, 20);
// delete the app
$I->executeJS('$(".app-details:contains(\''.$title.'\')").attr("act_id","true")');
$I->click('.app-details[act_id="true"] .delete-app');
$I->acceptPopup();
$I->waitForElementNotVisible('.app-details[act_id="true"]', 10);
