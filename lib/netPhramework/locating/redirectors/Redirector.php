<?php

namespace netPhramework\locating\redirectors;

use netPhramework\common\Variables;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\core\Node;
use netPhramework\exceptions\InvalidUri;
use netPhramework\locating\Redirectable;
use netPhramework\locating\rerouters\Rerouter;
use netPhramework\responding\ResponseCode;

/**
 * Relocates and dispatches. Used to prepare redirect Responses.
 */
class Redirector implements Node
{
	use LeafTrait;

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

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidUri
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect($this);
	}

	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}
}