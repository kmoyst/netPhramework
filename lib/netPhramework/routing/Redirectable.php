<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;

interface Redirectable
{
	/**
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function setResponseCode(ResponseCode $code):self;

	/***
	 * @return Reroutable
	 */
	public function getPath():Reroutable;

	/**
	 * @return Variables
	 */
	public function getParameters():Variables;
}