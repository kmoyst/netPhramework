<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\nodes\Resource;
use netPhramework\routing\Redirectable;
use netPhramework\routing\rerouters\Rerouter;

class Redirector extends Resource
{
	public function __construct(
		protected readonly Rerouter     $rerouter,
		protected readonly ?Variables   $parameters = null,
		protected readonly ResponseCode $code = ResponseCode::SEE_OTHER) {}

	/**
	 * @param Redirectable $redirectable
	 * @return void
	 */
	public function redirect(Redirectable $redirectable):void
	{
		$this->rerouter->reroute($redirectable->getPath());
		$redirectable->getParameters()->merge($this->parameters ?? []);
		$redirectable->setResponseCode($this->code);
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws PathException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect($this);
	}
}