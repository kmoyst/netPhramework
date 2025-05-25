<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;
use netPhramework\dispatching\Redirectable;
use netPhramework\dispatching\rerouters\Rerouter;

/**
 * Relocates and dispatches. Used to prepare redirect Responses.
 */
readonly class Redirector
{
	public function __construct(
		protected Rerouter     $relocator,
		protected ?Variables   $parameters = null,
		protected ResponseCode $code = ResponseCode::SEE_OTHER) {}

	/**
	 * @param Redirectable $redirectable
	 * @return void
	 */
	public function dispatch(Redirectable $redirectable):void
	{
		$this->relocator->relocate($redirectable->getPath());
		$redirectable->getParameters()->merge($this->parameters ?? []);
		$redirectable->setResponseCode($this->code);
	}
}