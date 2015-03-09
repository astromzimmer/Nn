<?php

namespace Nn\Trackers;

class Navajo implements TrackerInterface {

	public function sniff() {
		$ip = getenv('REMOTE_ADDR');
		$user_agent = getenv('HTTP_USER_AGENT');
		$referrer = getenv('HTTP_REFERER');
		$visitor = Visitor::find(array('ip'=>$ip,'user_agent'=>$user_agent),1);
		if(!$visitor) $visitor = new Visitor();
		$visitor->attr('ip',$ip);
		$visitor->attr('user_agent',$user_agent);
		$visitor->attr('referrer',$referrer);
		$visitor->save();
	}

	public function report() {
		$report = Visitor::count();
		return $report[0];
	}

}