## Automation Testing

### 背景
----------------------------------------------------
Popsugar本身采用的是敏捷模式下的开发方式，但对于测试来说还是利用了传统的测试方式，靠人工堆砌的方式进行测试。对于小的系统而言这种方式是最方便有效的，也容易找出问题，但对于大型的网站来说，由于网站页面的复杂性，显然人工测试方式没法每次都能覆盖所有方面，尤其当开发人员进行一些小的修改或者做一次hotfix的操作，再让人工再对网站其它部分做一次回归测试，显然这是比较浪费资源的。是否这些能交给机器来做呢，总归说来回归测试要做的内容基本上都是一致的，答案当然是肯定的。

### 解决方案
----------------------------------------------------
要模拟人类访问浏览器进行测试，这里需要用到一个必备可少的模拟服务 Selenium,
Selenium可以模拟用户打开浏览器，访问网站，并对网站进行一系列的操作。有了模拟器，当然要针对相应的操作步骤编写脚本， Popsugar网站由于是使用PHP+MYSQL的解构，因此我们也使用了Codeception这个可以使用PHP语言来编写步骤脚本的工具。这样也方便开发的人员能快速上手。

基本编写测试脚本的工具都有了，但是我们需要对编写的脚本进行一个管理，这里我们选择使用了github来管理所有编写的脚本。

有了管理，我们就要思考如何部署这些测试脚本，尤其是当开发人员修改或者增加一些功能后能自动的部署开发人员的代码到指定测试机器，并对其进行测试，这里我们选择使用Jenkins这个部署工具来实现这个，具体如何来实现，在下面会详细描述。

综上所述我们采用 Jenkins+github+codeception+Selenium来进行整个自动化测试的操作。

不过考虑以后测试脚本会有很多，然后我们需要提供他们的运行速度，因此并行运行他们是一个不错的解决方案，在这里我们编写了一个并行运行的脚本WrapperTool来实现这个。

### 安装&配置
----------------------------------------------------
#### Jenkins
从[Jenkins网站](https://jenkins.io/download/)直接下载对应系统的版本，然后点击安装就行。我这边是windows版本的，因此直接下载windows版本的安装。

#### github
github是一个代码仓库管理网站，对于本地来说需要安装git来进行管理。

这边提供一个windows版本的git[下载地址](https://git-for-windows.github.io/)
直接下载就可以安装了。

#### codeception

这里提供比较完整的[安装说明](http://codeception.com/install)

#### Selenium

从[selenium网站](http://www.seleniumhq.org/download/)下载 selenium-server-standalone-xxx.jar (xxx表示版本号)

从[这里](https://sites.google.com/a/chromium.org/chromedriver/)下载chromedriver

确保你本地已有java 运行环境

我这个是放在windows下，因此执行一下命令可以运行selenium server
java -Dwebdriver.chrome.driver=E:\chromedriver\2.25\chromedriver_win32\chromedriver.exe -jar e:\autotesting\testing\selenium-server-standalone-2.39.0.jar

ps: 使用chromedriver的目的是可以运行chrome浏览器，因此在下载chromedriver时注意支持的版本。

#### WrapperTool

首先这个脚本需要有java环境才能运行，因此确保本地已经有java的环境

从[这里](https://github.com/PopSugar/qa-tools/tree/master/Wrapper)下载devQAWrapper.phar 这个文件。

因为我是windows版本，因此我写了一个bat脚本来运行这个phar包，脚本内容如下

>@ECHO OFF

>SET BIN_TARGET=E:\java_pj\process\qa-tools\Wrapper\devQAWrapper.phar

>php "%BIN_TARGET%" %*

脚本文件名我命名为 wrapper, 然后把该脚本文件放到系统路径中去，这样可以直接在命令行 运行 wrapper执行该脚本。

### Let start
----------------------------------------------------
在以上环境全部部署好以后，我们就可以开始了。




