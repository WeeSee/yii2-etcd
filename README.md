Yii2-etcd
=============

Yii2 extension to access Etcd service.

Etcd is a key-value store for distributed systems.



Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist weesee/yii2-etcd "*"
```

or add

```
"weesee/yii2-etcd": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by:


```php 

    use weesee\etcd\Etcd;
    
    // setup connection to Etcd
    // setting root means all key are appended to this path
    $etcd = new Etcd([
        'etcdUrl' => 'http://127.0.0.1:2379',
        'root'=>"/yii2-etcd-test/"
    ]);
    
    // write key value pairs to etcd
    if ($etcd->exists("name"))
        $etcd->update("name","value");  // updates "/yii2-etcd-test/name"
    else
        $etcd->set("name","value");     // sets "/yii2-etcd-test/name"

    // remove key
    $etcd->removeKey("name");           // removes "/yii2-etcd-test/name"
    
    // get keys with values in current directory "/yii2-etcd-test"
    // as ArrayDataProvider. Simple to use for GidViews,...
    $dataProvider = $etcd->getKeyValueAsDataProvider(); 

```

       
Credits
-------

Thanks for your great job which this Yii2-extension is build on:

* [Activecollab/etcd](https://github.com/activecollab/etcd.git)
* [Coreos/etcd](https://github.com/coreos/etcd)

Author / Licence
----------------

WeeSee <weesee@web.de>

GNU GENERAL PUBLIC LICENSE, Version 3, 29 June 2007
