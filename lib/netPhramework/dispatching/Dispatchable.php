<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\responding\ResponseCode;

interface Dispatchable
{
	public function getPath():DispatchablePath;
	public function getParameters():Variables;
	public function seeOther():Dispatchable;
	public function redirect(ResponseCode $code):Dispatchable;
}