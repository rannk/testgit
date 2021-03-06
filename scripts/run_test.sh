#!/bin/bash
if [ $TRAVIS_BRANCH = 'deploy' ] then
docker pull aerokube/selenoid:latest-release
docker pull selenoid/vnc_chrome:66.0
docker run -d  --name selenoid  -p 4444:4444  -v /var/run/docker.sock:/var/run/docker.sock    -v `pwd`/config/:/etc/selenoid/:ro  aerokube/selenoid:latest-release  --conf /etc/selenoid/browsers.json -timeout 70s
sudo ln -f -s `pwd`/test/codeception/qaTools/lib/codecept /usr/local/bin/codecept
sudo mkdir /usr/local/ccparallel
sudo ln -f -s `pwd`/test/codeception/qaTools/lib/ccparallel.jar  /usr/local/ccparallel/ccparallel.jar
chmod -R 777 `pwd`/test/codeception/qaTools/lib
php test/codeception/qaTools/devQAWrapper.phar -R -e dev4chromeEnv -c ./test/codeception --detail_fail_cases --hostip 127.0.0.1 -p 4 --clear_data --detail_fail_cases -g glow-web --retry 3
fi