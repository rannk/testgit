#! /usr/local/bin/php

<?php

class installWrapper
{
    function __construct()
    {
    }

    function __destruct()
    {
    }

    function getOSType() {
        $ostype = "";

        $osinfo = php_uname();
        $pos = strpos($osinfo, 'inux');
        if ($pos != false) {
            # it is linux, now we need to check what kind of linux it is
            $result = shell_exec('cat /etc/issue');
            if (strpos($result, 'entOS') != false  || strpos($result, 'mazon') != false ) {
                # it is CentOS
                $ostype="CentOS";
            } else {
                $pos = strpos($result, 'buntu');
                if ($pos != false) {
                    $ostype="Ubuntu";
                }
            }
        } else {
            $pos = strpos($osinfo, 'arwin');
            if ($pos != false) {
                # it is MAC OS
                $ostype="MACOSX";
            } else {
                # it is windows
                $ostype="Win";
            }
        }

        return $ostype;
    }

    function checkXvfb()
    {
        $ostype = $this->getOSType();
        if ( $ostype == "Ubuntu") {
            if (file_exists("/usr/bin/Xvfb")) {
                echo "check the Xvfb ------------------------------------------------------------ OK\n";
            } else {
                echo "install the Xvfb ... ...\n";
                $cmd = "sudo apt-get install xvfb x11-xkb-utils xfonts-100dpi xfonts-75dpi xfonts-scalable xfonts-cyrillic x11-apps";
                $ret = passthru($cmd);
            }
            return true;
        }

        if ( $ostype == "CentOS") {
            if (file_exists("/usr/bin/Xvfb")) {
                echo "check the Xvfb ------------------------------------------------------------ OK\n";
            } else {
                echo "install the Xvfb ... ...\n";
                $cmd = "sudo yum install xorg-x11-server-Xvfb";
                $ret = passthru($cmd);
            }
            return true;
        }
    }

    function checkJava()
    {
        if ($this->getOSType() == "Ubuntu") {
            $ret = shell_exec("which java");
            if ($ret == "") {
                echo "install the openjdk-7-jdk\n";
                $cmd = "sudo apt-get install openjdk-7-jdk";
                $ret = passthru($cmd);
            } else {
                echo "check the Java environment ------------------------------------------------ OK\n";
            }
        }

        if ($this->getOSType() == "CentOS") {
            $ret = shell_exec("which java");
            if ($ret == "") {
                echo "install the openjdk-7-jdk\n";
                $cmd = "sudo yum install java-1.7.0-openjdk";
                $ret = passthru($cmd);
            } else {
                echo "check the Java environment ------------------------------------------------ OK\n";
            }
        }

    }

    function getLinuxOSBit() {
        $osbittype = "jfdxfds";
        $cmd = "uname -a";
        $ret = shell_exec($cmd);
        $pos = strpos($ret, "GNU/Linux");
        if ($pos == false) {
            $pos = strpos($ret, "Darwin Kernel");
            if ($pos == false) {
                $osbittype = "unknow Linux/Unix";
            } else {
                $pos = strpos($ret, "x86_64");
                if ($pos == false) {
                    $osbittype = "mac32";
                } else {
                    $osbittype = "mac64";
                }
            }
        } else {
            $pos = strpos($ret, "x86_64");
            if ($pos == false) {
                $osbittype = "linux32";
            } else {
                $osbittype = "linux64";
            }
        }

        return $osbittype;
    }


    function checkChromeDiver($currentPath)
    {
        $chromedriver = $currentPath . "/vendor/popsugar/qa-tools/webdriver/chrome/chromedriver";
        $osbittype = $this->getLinuxOSBit();
        $chromedrive_zip = $currentPath . "/vendor/popsugar/qa-tools/webdriver/chrome/" . "chromedriver_" . $osbittype . ".zip";


        if(file_exists($chromedrive_zip)) {
            if (file_exists($chromedriver)) {
                shell_exec("rm -f $chromedriver");
            }

            $cmd = "unzip $chromedrive_zip -d $currentPath/vendor/popsugar/qa-tools/webdriver/chrome/ && chmod 777 $chromedriver ";

            if($this->getOSType() == "MACOSX") {
                $ret = shell_exec("/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --version");
                // could get the MAX OS chrome version
                if(stripos($ret, "oogle Chrome")) {
                    preg_match("/\d{1,}/", $ret, $matches);
                    if(count($matches) > 0) {
                        $driver_version = $this->getDriverVersionForChrome($matches[0]);
                        if($driver_version) {
                            shell_exec("curl -O https://chromedriver.storage.googleapis.com/{$driver_version}/chromedriver_mac64.zip");
                            if(file_exists("chromedriver_mac64.zip")) {
                                $cmd = "unzip chromedriver_mac64.zip -d $currentPath/vendor/popsugar/qa-tools/webdriver/chrome/ && chmod 777 $chromedriver";
                            }
                        }
                    }
                }
            }

            $ret = shell_exec($cmd);
            shell_exec("rm -f chromedriver_mac64.zip");
        }else {
            echo "check the chromedriver failed: don't find chromedriver file for this OS\n";
            exit;
        }

        if(!file_exists("/usr/local/bin")) {
            shell_exec("sudo mkdir /usr/local/bin");
        }

        $cmd = "sudo ln -f -s $chromedriver /usr/local/bin/chromedriver";
        $ret = shell_exec($cmd);
        echo "check the chromedriver ---------------------------------------------------- OK\n";
    }

    function getDriverVersionForChrome($chrome_version) {
        $newest_v = "73.0.3683.20";
        $v_arr = array("60"=>"2.32", "63" => "2.33", "65" => "2.35", "68" => "2.38", "70" => "2.42", "72" => "2.45");
        foreach($v_arr as $k => $v) {
            if($chrome_version < $k)
                return $v;
        }

        return $newest_v;
    }

    function checkSeleniumJar($currentPath)
    {
        if ( file_exists($currentPath . '/vendor/popsugar/selenium-server/selenium-server-standalone.jar')) {
            echo "update selenium web driver\n";
            $cmd = "cd $currentPath/vendor/popsugar/selenium-server/ && git pull";
            $ret = passthru($cmd);
        } else {
            $cmd = "mkdir -p $currentPath/vendor/popsugar/";
            $ret = shell_exec($cmd);

            echo "download selenium webdriver\n";
            $cmd = "cd $currentPath/vendor/popsugar/ && git clone https://github.com/netwing/selenium-server.git";
            $ret = passthru($cmd);
        }

        $cmd = "sudo ln -f -s  $currentPath/vendor/popsugar/selenium-server/selenium-server-standalone-*.jar  /usr/local/selenium-server-standalone.jar";
        $ret = passthru($cmd);

        echo "check the selenium-server-standalone.jar ---------------------------------- OK\n";
    }

    function mklogdir($currentPath) {
        if (file_exists("$currentPath/log")) {
            echo "check qaTool's log directory existence ------------------------------------ OK\n";
        } else {
            $cmd = "mkdir -p $currentPath/log";
            exec($cmd . " 2>&1");
        }

        exec("sudo chmod 777 -R $currentPath/log 2>&1");

        if (file_exists("$currentPath/../tests/_log")) {
            echo "check codeception log directory existence --------------------------------- OK\n";
        } else {
            $cmd = "mkdir -p $currentPath/../tests/_log";
            shell_exec($cmd);
        }

        exec("sudo chmod 777 -R $currentPath/../tests/_log 2>&1");
    }

    function composerUpdate($currentPath) {
         $path = $currentPath . "/vendor/popsugar/qa-tools/Wrapper/devQAWrapper.phar";
         if ( file_exists($path)) {
             echo "update qa-tools:\n";
             $cmd =  $cmd = "cd $currentPath/vendor/popsugar/qa-tools/ && git remote -v";
             exec($cmd . " 2>&1", $ret, $ret_stat);
             if(!stripos($ret[0], "git@github.com:PopSugar/qa-tools.git")) {
                 echo "you are not download qa-tools lib from right repository, please delete the $currentPath/vendor/popsugar/qa-tools directory and try again\n";
                 exit;
             }
             $cmd = "cd $currentPath/vendor/popsugar/qa-tools/ && git pull";
             passthru($cmd, $ret);
         } else {
            $cmd = "mkdir -p $currentPath/vendor/popsugar/";
            shell_exec($cmd);
            echo "download qa-tools:\n";
            $cmd = "cd $currentPath/vendor/popsugar/ && git clone git@github.com:PopSugar/qa-tools.git";
            passthru($cmd, $ret);
         }
        if($ret != "0") {
            echo "download qa-tools lib was failed. please check your network and have the permission to read Popsugar github repository.\n";
            if(file_exists("$currentPath/vendor/popsugar/qa-tools/")) {
                echo "you could delete the $currentPath/vendor/popsugar/qa-tools directory and try again\n";
            }
            exit;
        }
    }

    function checkCodeception($currentPath)
    {
        if(file_exists("$currentPath/vendor/popsugar/qa-tools/codeception/codecept")) {
            $cmd = "sudo ln -f -s $currentPath/vendor/popsugar/qa-tools/codeception/codecept /usr/local/bin/codecept";
            exec("chmod 777 $currentPath/vendor/popsugar/qa-tools/codeception/codecept");
        }else if(file_exists("$currentPath/vendor/bin/codecept")) {
            $cmd = "sudo ln -f -s $currentPath/vendor/bin/codecept /usr/local/bin/codecept";
        }

        if($cmd) {
            if(file_exists("/usr/local/bin/codecept")) {
                exec("rm /usr/local/bin/codecept 2>&1");
            }
            exec($cmd . " 2>&1", $ret, $ret_stat);
            if($ret_stat == "0")
                echo "check the codecept -------------------------------------------------------- OK\n";
            else {
                echo "check the codecept failed: " . $ret[0];
                exit;
            }
        }else {
            echo "check the codecept failed: you don't have the codecept lib, please reinstall\n";
            exit;
        }
    }

    function linktools($currentPath)
    {
        $cmd = "chmod 777 $currentPath/vendor/popsugar/qa-tools/Wrapper/devQAWrapper.phar";
        $ret = shell_exec($cmd);

        echo "install devQAWrapper ... ...\n";
        $cmd = "sudo ln -f -s  $currentPath/vendor/popsugar/qa-tools/Wrapper/devQAWrapper.phar  /usr/local/bin/devQAWrapper";
        exec($cmd . " 2>&1", $ret, $ret_stat);
        if($ret_stat != "0") {
            echo "link Wrapper tool was failed: " . $ret[0];
            exit;
        }

        if (file_exists("/usr/local/ccparallel/ccparallel.jar")) {
            echo "check the ccparallel.jar -------------------------------------------------- OK\n";
        } else {
            $cmd = "sudo mkdir -p /usr/local/ccparallel";
            $ret = shell_exec($cmd);
        }

        echo "install ccparallel.jar ... ...\n";
        $cmd = "sudo ln -f -s $currentPath/vendor/popsugar/qa-tools/ccparallel/jar/ccparallel.jar  /usr/local/ccparallel/ccparallel.jar";
        exec($cmd . " 2>&1", $ret, $ret_stat);
        if($ret_stat != "0") {
            echo "link ccparallel tool was failed: " . $ret[0];
            exit;
        }
    }

    function checkEnv() {
        $ret = shell_exec("unzip");
        if(!stripos($ret, "Usage: unzip")) {
            echo "install toolsWrapper failed: please install the unzip first.\n";
            exit;
        }

        exec("java -version 2>&1", $ret, $ret_stat);
        if($ret_stat != "0") {
            if($this->getOSType() == "MACOSX") {
                echo "install toolsWrapper failed: please install the java first.\n";
                exit;
            }
        }
    }

    function checkEnd() {
        exec("which devQAWrapper", $ret_wrapper, $ret_stat);
        if($ret_wrapper[0] != "/usr/local/bin/devQAWrapper") {
            echo "Warning: you have more than one Wrapper tool link, please delete the " . $ret_wrapper[0] . "\n";
        }

        exec("which codecept", $ret, $ret_stat);
        if($ret[0] != "/usr/local/bin/codecept") {
            echo "Warning: you have more than one codecept tool link, please delete the " . $ret[0] . "\n";
        }

        echo "install toolsWrapper success.\n";
    }
}

$dev = new installWrapper();
exec("cd " . dirname(__FILE__) . "/../../../ && pwd", $ret, $ret_stat);
$basedir = $ret[0];
if(!file_exists($basedir) || $ret_stat != "0") {
    echo "install failed: can't get popsugar site root directory.\n";
    exit;
}

if($dev->getOSType() == "Win") {
    echo "sorry we don't support install this wrapper on the windows, please install wrapper on the windows by manual\n";
    exit;
}

$dev->checkEnv();
$dev->composerUpdate($basedir);
$dev->checkXvfb();
$dev->checkJava();
$dev->checkChromeDiver($basedir);
$dev->checkSeleniumJar($basedir);
$dev->mklogdir($basedir);
$dev->checkCodeception($basedir);
$dev->linktools($basedir);
$dev->checkEnd();
