<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;
use netPhramework\responding\ResponseCode;

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