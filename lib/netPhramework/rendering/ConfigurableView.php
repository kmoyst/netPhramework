<?php

namespace netPhramework\rendering;

use netPhramework\common\Variables;

interface ConfigurableView extends Viewable, Wrappable
{
	/**
	 * @return Variables
	 */
	public function getVariables(): Variables;

	/**
	 * @return self
	 */
	public function getContent(): self;
}