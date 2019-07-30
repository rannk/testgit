#!/bin/bash
if [ $TRAVIS_BRANCH = "deploy" ]; then
    if [ ! $REG_TEST_DIR ];then
        TEST_DIR=`pwd`/test
    else
        if [ $REG_TEST_DIR = /* ]; then
            TEST_DIR=$REG_TEST_DIR
        else
            TEST_DIR=`pwd`/$REG_TEST_DIR
        fi
    fi
    docker pull aerokube/selenoid:latest-release
    docker pull selenoid/vnc_chrome:66.0
    docker run -d  --name selenoid  -p 4444:4444  -v /var/run/docker.sock:/var/run/docker.sock    -v $TEST_DIR/scripts/config/:/etc/selenoid/:ro  aerokube/selenoid:latest-release  --conf /etc/selenoid/browsers.json -timeout 70s
    sudo ln -f -s $TEST_DIR/codeception/qaTools/lib/codecept /usr/local/bin/codecept
    sudo mkdir /usr/local/ccparallel
    sudo ln -f -s $TEST_DIR/codeception/qaTools/lib/ccparallel.jar  /usr/local/ccparallel/ccparallel.jar
    chmod -R 777 $TEST_DIR/codeception/qaTools/lib
    php $TEST_DIR/codeception/qaTools/devQAWrapper.phar -R -e dev4chromeEnv -c $TEST_DIR/codeception --detail_fail_cases --hostip 127.0.0.1 -p 4 --clear_data --detail_fail_cases -g $REG_TEST_GROUP --retry 2 | tee /tmp/test_result.t
    cat /tmp/test_result.t | grep "Failed: [^0]"
fi

