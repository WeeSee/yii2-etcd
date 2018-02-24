<?php

/**
 * EtcdProxy
 *
 * @link https://github.com/WeeSee/yii2-etcd
 * @copyright Copyright (c) 2018 WeeSee
 * @license  https://github.com/WeeSee/yii2-etcd/blob/master/LICENSE
 */

namespace weesee\etcd;

use ActiveCollab\Etcd\Client as EtcdClient;

/**
 * This is a proxy class for ActiveCollab\Etcd\Client.
 * It is used to directly access ActiveCollab via subclassing.
 *
 * @author WeeSee <weesee@web.de>
 */
class EtcdProxy extends EtcdClient
{

}