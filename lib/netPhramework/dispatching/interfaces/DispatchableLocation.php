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
	 * Convenience method to set common Response code for redirect method.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @return $this
	 */
	public function seeOther():self;

	/**
	 * To set Response to redirectable with explicit code.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function redirect(ResponseCode $code):self;

	/***
	 * @return RelocatablePath
	 */
	public function getPath():RelocatablePath;

	/**
	 * @return Variables
	 */
	public function getParameters():Variables;
}