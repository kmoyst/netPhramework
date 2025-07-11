<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\resources\Leaf;
use netPhramework\routing\Redirectable;
use netPhramework\routing\rerouters\Rerouter;

class Redirector extends Leaf
{
	public function __construct(
		protected readonly Rerouter     $relocator,
		protected readonly ?Variables   $parameters = null,
		protected readonly ResponseCode $code = ResponseCode::SEE_OTHER) {}

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

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect($this);
	}
}