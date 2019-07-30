#!/bin/bash
#export REG_TEST_DIR=$REG_TEST_DIR
if [ ! $MY_DIR ]; then
    DIR = test
else
    DIR=$MY_DIR
fi
echo $DIR