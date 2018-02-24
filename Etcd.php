<?php

/**
 * Etcd
 * 
 * @link https://github.com/WeeSee/yii2-etcd
 * @copyright Copyright (c) 2018 WeeSee
 * @license  https://github.com/WeeSee/yii2-etcd/blob/master/LICENSE
 */

namespace weesee\etcd;

use Yii;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use weesee\etcd\EtcdProxy;

/**
 * Ths class Etcd is a Yii2 Component to access Etcd key value store from Yii2.
 *
 * Curl is used to finally access etcd. In case of an error, an exception
 * is thrown.
 *
 * @author WeeSee <weesee@web.de>
 */
class Etcd extends Component
{
	/**
	 * Url of Etcd-Server
	 * default: see in config/web.php
	 */
	public $etcdUrl;
	
	/**
	 *
	 */
	public $root = '/'; 
	
	/**
	 * Connection to EtcdProxy
	 * @var EtcdProxy
	 */
	protected $etcdProxy;	
	
	/**
     * Initialize the class with options
     * Options:
     * - etcdUrl: string     Url for Etcd-Service e.g. "http://etcd:2369"
     * - root: string        Root node for subsequent accesses, default: "/"
     */
	public function init()
	{
		parent::init();
		if (!$this->etcdUrl)
			throw new \yii\base\InvalidConfigException('etcdUrl must be set');
		$this->etcdProxy = new EtcdProxy($this->etcdUrl);
		// strip off trailing slash
		if ($this->root != "/")
			$this->root = rtrim($this->root,"/");
	}
	
	public function getFullKey($key)
	{
		if ($key=='.' || $key == '')
			return $this->root;
		return $key[0]=='/' ? $key : $this->root."/".$key;
	}
	
	public function set($key,$value,$ttl = null)
	{
		Yii::info("set key: $key = $value");
		Yii::info("fullkey: ".$this->getFullKey($key));
		return $this->etcdProxy->set($this->getFullKey($key),$value,$ttl);
	}
	
	public function update($key,$value,$ttl = null)
	{
		return $this->etcdProxy->set($this->getFullKey($key),$value,$ttl);
	}
	
	public function get($key)
	{
		return $this->etcdProxy->get($this->getFullKey($key));
	}
	
	public function exists($key)
	{
		return $this->etcdProxy->exists($this->getFullKey($key));
	}
	
	public function dirExists($dir)
	{
		return $this->etcdProxy->dirExists($this->getFullKey($dir));
	}
	
	public function removeKey($key)
	{
		return $this->etcdProxy->remove($this->getFullKey($key));
	}
	
	public function getKeyValueMap($dir,$withPathInKey = false)
	{
		$fullDir = $this->getFullKey($dir);
		if (!$this->dirExists($fullDir))
			return false;
		$map = $this->etcdProxy->getKeyValueMap($fullDir);
		if (count($map) && !$withPathInKey) {
			foreach($map as $key => $value) {
				$parts = explode('/',$key);
				$newKey = end($parts);
				unset($map[$key]);
				$map[$newKey] = $value;
			}
		}
		return $map;
	}
	
	public function getKeyValueAsDataProvider($dir=".",$withPathInKey = false)
	{
		$data = $this->getKeyValueMap($dir,$withPathInKey);
		$dataModels = [];
		$id = 0;
		if ($data)
			foreach ($data as $key => $value)
				$dataModels[] = ['id'=>$id++,'name'=>$key,'value'=>$value];
		$dataProvider = new ArrayDataProvider([
            'allModels' => $dataModels,
            'key' => 'name',
			'sort' => ['attributes' => ['name']],
        ]);
		return $dataProvider;
	}
}