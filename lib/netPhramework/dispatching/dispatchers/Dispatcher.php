<?php

namespace netPhramework\dispatching\dispatchers;

use netPhramework\common\Variables;
use netPhramework\dispatching\interfaces\DispatchableLocation;
use netPhramework\dispatching\relocators\Relocator;
use netPhramework\responding\ResponseCode;

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
	 * @param DispatchableLocation $dispatchable
	 * @return void
	 */
	public function dispatch(DispatchableLocation $dispatchable):void
	{
		$this->relocator->relocate($dispatchable->getPath());
		$dispatchable->getParameters()->merge($this->parameters ?? []);
		$dispatchable->redirect($this->code);
	}
}