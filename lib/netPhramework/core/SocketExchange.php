<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\dispatching\CallbackManager;
use netPhramework\dispatching\Dispatchable;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\MutableLocation;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\ReadableLocation;
use netPhramework\dispatching\ReadablePath;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NoContent;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\rendering\Wrapper;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\responding\Displayable;
use netPhramework\responding\Redirectable;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;
use netPhramework\responding\ResponseContent;

/**
 * This class implements FOUR distinct interfaces for segregation.
 *
 * 1) Exchange - for handlers (Components and Processes)
 * 2) Dispatchable - for Dispatchers to direct response redirects
 * 3) Location - to allow reading of Response redirects
 * 4) Response - delivers Response through Responder
 */
class SocketExchange implements Exchange, Dispatchable, Location, Response
{
	/**
	 * Display wrapper. Usually set by Socket and configured by Application
	 * Configuration.
	 *
	 * @var Wrapper
	 */
    private Wrapper $wrapper;
	/**
	 * Maintains original request Path.
	 *
	 * @var Path
	 */
	private Path $requestPath;
	/**
	 * Maintains original request Parameters.
	 *
	 * @var Variables
	 */
	private Variables $requestParameters;
	/**
	 * Cloned initially from request Path. Used for Response Path for redirects.
	 *
	 * @var Path
	 */
	private Path $path;
	/**
	 * Cloned initially from request Parameters.
	 * Used for Response Parameters for redirects.
	 *
	 * @var Variables
	 */
	private Variables $parameters;
	/**
	 * Response content. Delivered through responder. Usually set by Component.
	 *
	 * @var ResponseContent
	 */
	private ResponseContent $responseContent;
	/**
	 * Response code delivered through Responder. Usually set by Component.
	 * @var ResponseCode
	 */
	private ResponseCode $responseCode;
	/**
	 * Current Session.
	 *
	 * @var Session
	 */
	private Session $session;
	/**
	 * Centralized manager for callbacks.
	 *
	 * @var CallbackManager
	 */
	private CallbackManager $callbackManager;


	/** @inheritDoc */
	public function ok(Wrappable $content):self
	{
		$this->display($content, ResponseCode::OK);
		return $this;
	}

	/** @inheritDoc */
	public function dispatch(Dispatcher $fallback):self
	{
		$dispatcher = $this->callbackManager->callbackDispatcher() ?: $fallback;
		$dispatcher->dispatch($this);
		return $this;
	}

	/** @inheritDoc */
	public function error(Exception $exception): self
	{
		$code = ResponseCode::tryFrom($exception->getCode());
		$this->display($exception, $code);
		return $this;
	}

	/** @inheritDoc */
	public function getRequestPath(): Path
	{
		return clone $this->requestPath;
	}

	/** @inheritDoc */
	public function getRequestParameters(): Variables
	{
		return clone $this->requestParameters;
	}

	/** @inheritDoc */
	public function callbackLink(bool $chain = false):string|Location
	{
		return $this->callbackManager->callbackLink($chain);
	}

	/** @inheritDoc */
	public function callbackFormInput(bool $chain = false):HiddenInput
	{
		return new HiddenInput(
			$this->callbackManager->getCallbackKey(),
			$this->callbackLink($chain));
	}

	/** @inheritDoc */
	public function getSession(): Session
	{
		return $this->session;
	}

	/** @inheritDoc */
	public function getRequestLocation(): MutableLocation
	{
		return new MutableLocation(clone $this->path, clone $this->parameters);
	}

	/** @inheritDoc */
	public function getPath(): Path
	{
		return $this->path;
	}

	/** @inheritDoc */
    public function getParameters(): Variables
    {
        return $this->parameters;
    }

	/** @inheritDoc */
	public function seeOther(): self
	{
		$this->redirect(ResponseCode::SEE_OTHER);
		return $this;
	}

	/**
	 * Finalizes a redirect Response with specified ResponseCode.
	 * Uses the current state of Path and Parameters (Location) configured
	 * through direct interaction with this object's Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function redirect(ResponseCode $code):self
	{
		$location				= $this->generateReadableLocation();
		$this->responseContent  = new Redirectable($location);
		$this->responseCode     = $code;
		return $this;
	}

	/**
	 * Implements Response interface contract.
	 *
	 * @param Responder $responder
	 * @return void
	 * @throws NoContent
	 */
	public function deliver(Responder $responder): void
	{
		if(!isset($this->responseContent))
			throw new NoContent("No response content to deliver");
		$this->responseContent->relay($responder, $this->responseCode);
	}

	private function display(
		Wrappable $content, ResponseCode $code):self
	{
		$this->displayRaw($this->wrapper->wrap($content), $code);
		return $this;
	}


	private function displayRaw(Viewable $content, ResponseCode $code):self
	{
		$this->responseContent  = new Displayable($content);
		$this->responseCode     = $code;
		return $this;
	}

	/**
	 * Generates a readable copy of the currently configured Response Location.
	 *
	 * @return ReadableLocation
	 */
	private function generateReadableLocation(): ReadableLocation
	{
		$path       = clone $this->path;
		$parameters = clone $this->parameters;
		return new ReadableLocation($path, $parameters);
	}

	/**
	 * Injector for request Path.
	 * Modified by handling component for response.
	 *
	 * @param Path $path
	 * @return $this
	 */
	public function setPath(Path $path): self
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * Injector for request Parameters. Mutable.
	 * Modified by handling component for response.
	 *
	 * @param Variables $parameters
	 * @return $this
	 */
	public function setParameters(Variables $parameters): self
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * Injector for CallbackManager
	 *
	 * @param CallbackManager $manager
	 * @return $this
	 */
	public function setCallbackManager(CallbackManager $manager): self
	{
		$this->callbackManager = $manager;
		return $this;
	}

	/**
	 * Injector for Session
	 *
	 * @param Session $session
	 * @return $this
	 */
	public function setSession(Session $session): self
	{
		$this->session = $session;
		return $this;
	}

	/**
	 * Injector for current display wrapper
	 *
	 * @param Wrapper $wrapper
	 * @return $this
	 */
	public function setWrapper(Wrapper $wrapper): self
	{
		$this->wrapper = $wrapper;
		return $this;
	}
}