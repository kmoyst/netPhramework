<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public function getSession():Session;
	public function getEnvironment():Environment;
	public function getCallbackKey():string;
	public function getUploadManager():UploadManager;
}