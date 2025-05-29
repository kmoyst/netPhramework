<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;
use netPhramework\transfers\UploadManager;

interface RequestContext
{
	public function getSession():Session;
	public function getEnvironment():Environment;
	public function getCallbackKey():string;
	public function getUploadManager():UploadManager;
}