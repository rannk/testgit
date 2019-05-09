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
$I->waitForText('Interested in selling on Glow? Apply today.', 30);
