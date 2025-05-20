<?php

namespace netPhramework\dispatching;

use netPhramework\responding\ResponseCode;

/**
 * Adjustable location and receives response preparation
 */
interface Dispatchable extends Relocatable
{
	public function seeOther():Dispatchable;
	public function redirect(ResponseCode $code):Dispatchable;
}