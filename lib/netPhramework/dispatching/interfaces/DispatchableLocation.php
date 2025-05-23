<?php

namespace netPhramework\dispatching\interfaces;

use netPhramework\common\Variables;
use netPhramework\responding\ResponseCode;

/**
 * Used by Dispatcher to prepare Redirectable ResponseContent.
 */
interface DispatchableLocation
{
	/**
	 * To set Response to redirectable with explicit code.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function setResponseCode(ResponseCode $code):self;

	/***
	 * @return RelocatablePath
	 */
	public function getPath():RelocatablePath;

	/**
	 * @return Variables
	 */
	public function getParameters():Variables;
}