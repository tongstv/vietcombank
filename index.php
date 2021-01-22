<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.
//namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition; // https://github.com/php-webdriver/php-webdriver/wiki/HowTo-Wait

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Firefox\FirefoxDriver;

// Chrome
require_once('vendor/autoload.php');


include '2Captcha.php';
$desiredCapabilities = DesiredCapabilities::firefox();

// Disable accepting SSL certificates
$desiredCapabilities->setCapability('acceptSslCerts', false);

// Run headless firefox
//$desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);
$args = [

    '--display', ':1',
    //  '-headless',

];
$desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => $args]);
//$desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ["-profile", " /tmp/111"]]);
//$desiredCapabilities->setCapability('moz:firefoxOptions', ['binary' => "/usr/bin/firefox"]);
//$desiredCapabilities->setCapability('moz:firefoxOptions', ['binary' => "C:/Program Files (x86)/Mozilla Firefox/firefox.exe"]);
//$desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ['--no-sandbox ']]);
//$serverUrl = 'http://irobot.mobi:1986';


$serverUrl = 'http://irobot.mobi:4444/wd/hub';


$session = "vietcombank";

if (file_exists($session)) {
    $sessionId = file_get_contents($session);
}

function a($a)
{
    return $a['id'];
}

if (isset($sessionId)) {

    $Allsession = RemoteWebDriver::getAllSessions($serverUrl);

    $Allsession = array_map('a', $Allsession);

    if (in_array($sessionId, $Allsession)) {

        try {

            print  "Connect session";
            $web_driver = RemoteWebDriver::createBySessionID($sessionId, $serverUrl);

        } catch (Exception $e) {


            $web_driver = RemoteWebDriver::create($serverUrl, $desiredCapabilities, 600000, 600000);
            file_put_contents($session, $web_driver->getSessionID());

        }

    } else {


    }

} else {

    $fp = new FirefoxProfile();
    $desiredCapabilities->setCapability(WebDriverCapabilityType::NATIVE_EVENTS, true);
    if (isset($proxy['ip'])) {
        $proxy['port'] = ArrayHelper::getValue($proxy, 'port');
        $fp->setPreference('network.proxy.ssl_port', $proxy['port']);
        $fp->setPreference('network.proxy.ssl', $proxy['ip']);
        $fp->setPreference('network.proxy.http_port', $proxy['port']);
        $fp->setPreference('network.proxy.http', $proxy['ip']);
        $fp->setPreference('network.proxy.type', 1);
    }
    //$desiredCapabilities->setCapability(FirefoxDriver::PROFILE, $fp);


    $web_driver = RemoteWebDriver::create($serverUrl, $desiredCapabilities, 600000, 600000);
    file_put_contents($session, $web_driver->getSessionID());
}


$web_driver->get("https://vcbdigibank.vietcombank.com.vn");


$curl = $web_driver->getCurrentURL();
echo $curl;

if (stristr($curl, 'info')) {


} else {


    $web_driver->wait(120, 2000)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('username'))
    );

    $element = $web_driver->findElement(WebDriverBy::id("username"));
    if ($element) {
        $element->sendKeys("0914779999");

        $element = $web_driver->findElement(WebDriverBy::id("app_password_login"))->sendKeys('@Hyn2106861');


        $img = $web_driver->findElement(WebDriverBy::className('captcha'))->findElement(WebDriverBy::tagName('img'));

        $img->takeElementScreenshot('mycaptcha.png');

        /*
        // $web_driver->get($img->getAttribute('src'));
        $web_driver->takeScreenshot('./mycaptcha.png');


        $local = $img->getLocation();
        $x = $local->getX();
        $y = $local->getY();
        $width = $img->getSize()->getWidth();
        $height = $img->getSize()->getHeight();


        $img_r = imagecreatefrompng('./mycaptcha.png');

        $size = min(imagesx($img_r), imagesy($img_r));
        $im2 = imagecrop($img_r, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

        if ($im2 !== FALSE) {
            imagepng($im2, 'xxx.png');
            imagedestroy($im2);
        }


        imagedestroy($img_r);
*/
        //file_put_contents("./mycaptcha.jpg", $src);
        $web_driver->wait(5);

        $o2c = new _2Captcha;

        if ($o2c->setKey('cc14ca035b6fc5c080a46971643911b6') &&
            $o2c->setImage('./mycaptcha.png') &&
            $o2c->run()) {
            $text = $o2c->getText();
            print "Captcha Text: " . $text;


            $web_driver->findElement(WebDriverBy::name('captcha'))->sendKeys($text);


            $web_driver->findElement(WebDriverBy::id('btnLogin'))->click();
            echo $web_driver->getCurrentURL();
            sleep(2);

            $web_driver->findElement(WebDriverBy::xpath('//*[@id="maincontent"]/ng-component/div/div[5]/ng-component/div[2]/div[2]/div/div/div[2]/div[1]/div/div[2]/a'))->click();
            $web_driver->findElement(WebDriverBy::xpath('//*[@id="ChiTietTaiKhoan"]/div/div/div[7]/div/div[2]/div/a'))->click();
            $web_driver->findElement(WebDriverBy::xpath('//*[@id="ChiTietTaiKhoan"]/div/div/div[8]/div/nav/a[2]'))->click();
            $web_driver->findElement(WebDriverBy::xpath('//*[@id="ChiTietTaiKhoan"]/div/div/div[8]/div/nav/a[2]'))->click();


            $e = $web_driver->findElement(WebDriverBy::className('list-info'))->getText();

            print $e;
            //  $web_driver->findElement(WebDriverBy::linkText("Chi tiết"))->click();

            //$web_driver->findElement(WebDriverBy::xpath('//*[@id="maincontent"]/ng-component/div/div[5]/ng-component/div/div/div[2]/div/div/div[1]/div[2]'))->click();
            //$web_driver->findElement(WebDriverBy::cssSelector(".select2-search__field"))->sendKeys("7799999");
            //    $web_driver->findElement(WebDriverBy::cssSelector("#toanbo .list-info-item:nth-child(1) .td-xs:nth-child(1) > .list-info-txt-sub"))->click();
            //  $web_driver->findElement(WebDriverBy::linkText("Tìm kiếm"))->click();
            //$web_driver->findElement(WebDriverBy::linkText("Tiền vào"))->click();

            // $html = $web_driver->findElement(WebDriverBy::xpath('//*[@id="vao"]/div'))->getText();

        }


        //$element->submit();
    }
}


//https://2captcha.com/2captcha-api
///$web_driver->findElement(WebDriverBy::className('captcha'))->save_screenshot('captcha.png');

//$web_driver->quit();
/*
docker run --name "test" -d -p 991:6901 -p992:4444-e VNC_PW=123  consol/centos-xfce-vnc
docker exec -u 0 -it  chrome2  sh
 yum -y install chromedriver java-1.8.0-openjdk \
 && wget https://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar \
 && mv selenium-server-standalone-3.141.59.jar selenium-server-standalone.jar \
&& chromedriver </dev/null &>/dev/null &\
&& java -jar selenium-server-standalone.jar </dev/null &>/dev/null
*/
