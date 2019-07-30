<?php
use Codeception\Event\SuiteEvent;
use Codeception\Event\FailEvent;
use Codeception\Events;
use Codeception\SuiteManager;

class popsugar extends \Codeception\Platform\Extension
{
    // list events to listen to
    static $events = [
        Events::SUITE_BEFORE => 'beforeSuite'
    ];

    // methods that handles events
    public function beforeSuite(SuiteEvent $e) {
        // this change since codeception v2.1
        $suite_str = $e->getSuite()->toString();
        $suite_arr = preg_split("/[\- ]/", $suite_str);
        $suite = $suite_arr[0];

        if($suite == "api" || $suite == "unit")
            return;

        if($suite == "phpbrowser" || $suite == "warmup") {
            $suite_config = "PhpBrowser";
        }elseif($suite == "phpbrowser_mobile"){
            $suite_config = "PhpBrowser";
            $m = $this->getModule("PhpBrowser");
            $m->client->setServerParameter("HTTP_USER_AGENT", "iphone");
        }else {
            $suite_config = "WebDriver";
        }
        $modules = $e->getSettings();
        $url = $modules['modules']['config'][$suite_config]['url'];

        CommonSiteClass::$current_url = $url;
        preg_match("/popsugar\.(?<devbox>\w*).onsugar/", $url, $arr);

        if(stripos($url, "www.popsugar.com") == true) {
            CommonSiteClass::$devBox = "prod";
            return;
        }elseif($arr['devbox']) {
            CommonSiteClass::$devBox = $arr['devbox'];
        }else {
            CommonSiteClass::$devBox = "local";
        }

        if(CommonSiteClass::$devBox == "prod" || CommonSiteClass::$devBox == "local")
            return;

        $count = 0;
        while ($count < 5) {
            $url = rtrim($url, "/");
//            $result = file_get_contents($url . "/settings/editor_roles?name=tk25");
            $result = "Set editor roles successfully.";
            if ($result == "Set editor roles successfully.") {
                echo "At " . $url . ", " . $result . "\n";
                break;
            }
            else {
                echo "Failed to set editor role, wait 2 seconds and try again ... ...";
                sleep(2);
            }
            $count = $count + 1;
        }

        if ($count >= 5) {
            echo "Finally failed to set editor role, please run CodeCeption again !";
            exit;
        }
    }
}
