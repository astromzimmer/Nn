<?php

namespace Nn\Trackers;

interface TrackerInterface {

	public function sniff();

	public function report();

}