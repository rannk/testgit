<?php
/*
 * @test_cover PS Edit Node
 */
$scenario->group('glow-web');

$I = new SeleniumGuy($scenario);
$I->wantTo('Run a basic test');

$title = "Test Visiting Glow Page";
// fill basic information
$I->amOnUrl("https://dev.glow.popsugar.com/");
$I->waitForElement(".navbar", 20);
