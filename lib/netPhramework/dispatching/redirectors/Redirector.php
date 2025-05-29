<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\dispatching\Redirectable;
use netPhramework\dispatching\rerouters\Rerouter;
use netPhramework\responding\ResponseCode;

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
	public function redirect(Redirectable $redirectable):void
	{
		$this->relocator->reroute($redirectable->getPath());
		$redirectable->getParameters()->merge($this->parameters ?? []);
		$redirectable->setResponseCode($this->code);
	}
}