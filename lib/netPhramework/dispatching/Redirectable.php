<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;

interface Redirectable
{
	/**
	 * To set Response to redirectable with explicit code.
	 * Finalizes the dispatch based on current MutablePath and Parameters.
	 *
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