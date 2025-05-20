<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\dispatching\CallbackManager;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\ReadableLocation;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NoContent;
use netPhramework\rendering\Wrapper;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\responding\Displayable;
use netPhramework\responding\Redirectable;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;
use netPhramework\responding\ResponseContent;

class SocketExchange implements Exchange, Response
{
    private Wrapper $wrapper;
	private Path $path;
	private Variables $parameters;
	private ResponseContent $responseContent;
	private ResponseCode $responseCode;
	private Session $session;
	private CallbackManager $callbackManager;

	public function getCallbackKey():string
	{
		return $this->callbackManager->getCallbackKey();
	}

	/**
	 * @param Dispatcher $fallback
	 * @return $this
	 * @throws Exception
	 */
	public function callback(Dispatcher $fallback):SocketExchange
	{
		$dispatcher = $this->callbackManager->resolve() ?: $fallback;
		$dispatcher->dispatch($this);
		return $this;
	}

	public function stickyCallback():string|Location
	{
		return $this->callbackManager->sticky();
	}

	public function setCallbackManager(CallbackManager $manager): SocketExchange
	{
		$this->callbackManager = $manager;
		return $this;
	}

	public function setSession(Session $session): SocketExchange
	{
		$this->session = $session;
		return $this;
	}

	public function getSession(): Session
	{
		return $this->session;
	}

	public function setWrapper(Wrapper $wrapper): SocketExchange
    {
        $this->wrapper = $wrapper;
		return $this;
    }

    public function setPath(Path $path): SocketExchange
	{
		$this->path = $path;
		return $this;
	}

	public function setParameters(Variables $parameters): SocketExchange
	{
		$this->parameters = $parameters;
		return $this;
	}

    public function getParameters(): Variables
    {
        return $this->parameters;
    }

	public function getPath(): Path
	{
        return $this->path;
	}

	public function deliver(Responder $responder): void
	{
		if(!isset($this->responseContent))
			throw new NoContent("No response content to deliver");
		$this->responseContent->relay($responder, $this->responseCode);
	}

	public function ok(Wrappable $content):SocketExchange
    {
        $this->display($content, ResponseCode::OK);
		return $this;
    }

	public function display(
		Wrappable $content, ResponseCode $code):SocketExchange
	{
		$this->displayRaw($this->wrapper->wrap($content), $code);
		return $this;
	}

    public function seeOther(): SocketExchange
	{
		$this->redirect(ResponseCode::SEE_OTHER);
		return $this;
	}

	public function displayRaw(
		Viewable $content, ResponseCode $code):SocketExchange
	{
		$this->responseContent  = new Displayable($content);
		$this->responseCode     = $code;
		return $this;
	}

	public function redirect(ResponseCode $code):SocketExchange
	{
        $path                   = clone $this->path;
        $parameters             = clone $this->parameters;
        $location               = new ReadableLocation($path, $parameters);
        $this->responseContent  = new Redirectable($location);
		$this->responseCode     = $code;
		return $this;
	}

	public function error(Exception $exception): SocketExchange
	{
		$code = ResponseCode::tryFrom($exception->getCode()) ??
				ResponseCode::SERVER_ERROR;
		$this->display($exception, $code);
		return $this;
	}
}