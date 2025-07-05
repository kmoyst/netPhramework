<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public function getSession():Session;
	public function getEnvironment():Environment;
	public function getCallbackManager():CallbackManager;
	public function getFileManager():FileManager;
}