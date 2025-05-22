<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\responding\ResponseCode;

/**
 * Adjustable location and receives response preparation
 */
interface Dispatchable extends Relocatable
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

	/** @inheritDoc */
	public function getPath():RelocatablePath;

	/** @inheritDoc */
	public function getParameters():Variables;
}