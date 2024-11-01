<?php
namespace ydn;
ini_set('display_errors','on');
ini_set('error_reporting', E_ALL );

class Checker {
	private $obj;
	
	public function setObj($obj) {
		$this->obj = $obj;
	}
	
	public function getObj() {
		return $this->obj;
	}
	
	public function isAllow() {
		$status = true;
		
		$obj = $this->getObj();
		
		if(empty($obj)) {
			return false;
		}
		
		if(YDN_PKG_VERSION > YDN_FREE_VERSION) {
			require_once YDN_CLASS_PATH.'CheckerPro.php';
			$obj = new CheckerPro();
			$obj->setCheckerObj($this);
			$isAllow = $obj->allowToLoad();
		
			if(!$isAllow) {
				return $isAllow;
			}
		}
		
		return $status;
	}
	
	public function devideSettings($settings) {
		$devidedData = array();
		foreach ($settings as $key => $setting) {
			if(empty($setting['key2'])) {
				continue;
			}
			if($setting['key2'] == 'is') {
				$devidedData['permissive'][] = $setting;
			}
			if($setting['key2'] == 'isnot') {
				$devidedData['forbidden'][] = $setting;
			}
		}
		
		return $devidedData;
	}
	
	private function isSatisfyForConditions($settings) {
		$status = false;
		if(empty($settings)) {
			return $status;
		}
		foreach ($settings as $setting) {
			$currentStatus = $this->isSatisfyForCondition($setting);
			if($currentStatus) {
				$status = $currentStatus;
				break;
			}
		}
		
		return $status;
	}
	
	private function isSatisfyForCondition($setting) {
		if(empty($setting['key1'])) {
			return false;
		}
		$key = $setting['key1'];
		$postId = get_queried_object_id();
		if($key == 'everywhere') {
			return true;
		}
		if(strpos($key, 'all_') == 0) {
			$currentPostType = get_post_type($postId);
			if ('all_'.$currentPostType == $key) {
				return true;
			}
		}
		if(strpos($key, 'selected_') == 0) {
			$selectTargetIds = array_keys($setting['key3']);
			if(in_array($postId, $selectTargetIds)) {
				return true;
			}
		}
		
		return false;
	}
}