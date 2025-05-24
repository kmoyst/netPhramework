<?php

namespace netPhramework\dispatching\dispatchers;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;
use netPhramework\dispatching\Redirectable;
use netPhramework\dispatching\relocators\Relocator;

/**
 * Relocates and dispatches. Used to prepare redirect Responses.
 */
readonly class Dispatcher
{
	public function __construct(
		protected Relocator $relocator,
		protected ?Variables $parameters = null,
		protected ResponseCode $code = ResponseCode::SEE_OTHER) {}

	/**
	 * @param Redirectable $dispatchable
	 * @return void
	 */
	public function dispatch(Redirectable $dispatchable):void
	{
		$this->relocator->relocate($dispatchable->getPath());
		$dispatchable->getParameters()->merge($this->parameters ?? []);
		$dispatchable->setResponseCode($this->code);
	}
}