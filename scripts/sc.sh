#!/bin/bash
#export REG_TEST_DIR=$REG_TEST_DIR
    if [ ! $REG_TEST_DIR ];then
        TEST_DIR=`pwd`/test/
    else
        if [ $REG_TEST_DIR = /* ]; then
            TEST_DIR=$REG_TEST_DIR
        else
            TEST_DIR=`pwd`/$REG_TEST_DIR
        fi
    fi
echo $TEST_DIR