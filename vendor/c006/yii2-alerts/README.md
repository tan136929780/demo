Yii2 Alerts
===================

**Updated: August, 2015**

+ Added scss and css files to set your own colors

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

`
php composer.phar require --prefer-source "c006/yii2-alerts" "dev-master"
`

or add

`
"c006/yii2-alerts": "dev-master"
`

to the require section of your `composer.json` file.


Required
--------

+ ***jQuery***

+ **yii \ widgets \ Bootstrap**


Options
-------

**message =>**  {string}  
` Alert message (HTML) `

**alert_type =>**  {string}

+ Alerts::ALERT_DANGER
+ Alerts::ALERT_WARNING
+ Alerts::ALERT_INFO
+ Alerts::ALERT_SUCCESS

**close =>**  {boolean}  
` Show close link for alert `

**countdown =>**  {int}  
` Automatically remove alert in X seconds `


Demo
-------

Demo: [http://demo.c006.us](http://demo.c006.us)


Usage
-----

Set message

>
    Alerts::setMessage('Session Timeout');
    Alerts::setAlertType(Alerts::ALERT_WARNING);
    Alerts::setCountdown(10); /* optional */
    

Display message
>
    <?= Alerts::widget() ?>





Comments / Suggestions
--------------------

Please provide any helpful feedback or requests.

Thanks.


































