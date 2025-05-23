<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\dispatching\CallbackManager;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\MutableLocation;
use netPhramework\dispatching\Path;
use netPhramework\exceptions\Exception;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\BasicResponse;
use netPhramework\responding\DisplayableContent;
use netPhramework\responding\Redirection;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class SocketExchange implements Exchange
{
	/**
	 * BasicResponse wrapper. Usually set by Socket and configured by Application
	 * Configuration.
	 *
	 * @var Wrapper
	 */
    private Wrapper $wrapper;
	/**
	 * The original request Path - remains immutable.
	 *
	 * @var Path
	 */
	private Path $path;
	/**
	 * The original request Parameters - remains immutable.
	 *
	 * @var Variables
	 */
	private Variables $parameters;
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
	/**
	 * Response set by Exchange handler
	 *
	 * @var Response
	 */
	private Response $response;

	/** @inheritDoc */
	public function redirect(Dispatcher $fallback):self
	{
		$path 		= clone $this->path;
		$parameters = clone $this->parameters;
		$response 	= new Redirection($path, $parameters);
		$dispatcher = $this->callbackManager->callbackDispatcher() ?: $fallback;
		$dispatcher->dispatch($response);
		$this->response = $response;
		return $this;
	}

	/** @inheritDoc */
	public function ok(Wrappable $content):self
	{
		$this->wrappedDisplay($content, ResponseCode::OK);
		return $this;
	}

    /**
     * Centralized method for wrapping wrappable content a passing to
     * final display method.
     *
     * @param Wrappable $content
     * @param ResponseCode $code
     * @return void
     */
	private function wrappedDisplay(Wrappable $content, ResponseCode $code):void
	{
		$this->directDisplay($this->wrapper->wrap($content), $code);
	}

	/**
	 * Centralized method for creating BasicResponse responses.
	 *
	 * @param Viewable $content
	 * @param ResponseCode $code
	 * @return void
	 */
	private function directDisplay(Viewable $content, ResponseCode $code):void
	{
		$responseContent = new DisplayableContent($content);
		$this->response  = new BasicResponse($responseContent, $code);
	}

	/** @inheritDoc */
	public function error(Exception $exception): self
	{
		$this->response = $exception->setWrapper($this->wrapper);
		return $this;
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
	public function getPath(): Path
	{
		return clone $this->path;
	}

	/** @inheritDoc */
	public function getParameters(): Variables
	{
		return clone $this->parameters;
	}

	/** @inheritDoc */
	public function getLocation(): MutableLocation
	{
		return new MutableLocation(clone $this->path, clone $this->parameters);
	}

	/** @inheritDoc */
	public function getSession(): Session
	{
		return $this->session;
	}

	/**
	 * Sets the Request Path (usually by Socket)
	 *
	 * @param Path $path
	 * @return $this
	 */
	public function setPath(Path $path): SocketExchange
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * Sets the Request Parameters (usually by Socket)
	 *
	 * @param Variables $parameters
	 * @return $this
	 */
	public function setParameters(Variables $parameters): SocketExchange
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

	/**
	 * For returning the Response set by Exchange handlers to Socket
	 *
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}
}