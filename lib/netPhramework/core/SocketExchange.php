<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\dispatching\MutableLocation;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\Redirection;
use netPhramework\exceptions\Exception;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\rendering\Display;
use netPhramework\rendering\View;
use netPhramework\rendering\ViewConfiguration;
use netPhramework\rendering\Wrapper;

class SocketExchange implements Exchange
{
    /**
     * The request Path. SocketExchange protects immutability.
     *
     * @var Path
     */
	private Path $path;
    /**
     * The request Parameters. SocketExchange protects immutability.
     *
     * @var Variables
     */
	private Variables $parameters;
	private Session $session;
	private CallbackManager $callbackManager;
    private Wrapper $wrapper;
	private Response $response;

	/** @inheritDoc */
	public function redirect(Dispatcher $fallback):Variables
	{
		$redirection 	= new Redirection(clone $this->path);
		$callback		= $this->callbackManager->callbackDispatcher();
		($callback??$fallback)->dispatch($redirection);
		$this->response = $redirection;
		return $redirection->getParameters();
	}

	/** @inheritDoc */
	public function ok(View $view):ViewConfiguration
	{
		return $this->display($view, ResponseCode::OK);
	}

    /** @inheritDoc */
	public function display(
		View $view, ResponseCode $code):ViewConfiguration
	{
		$wrappedViewable = $this->wrapper->wrap($view);
        $this->response  = new Display($wrappedViewable, $code);
		return $view;
	}

	/** @inheritDoc */
	public function error(Exception $exception, Dispatcher $fallback): void
	{
        try {
            //$vars = $this->redirect($fallback);
			$this->redirect($fallback);
            $this->session->addErrorMessage(
                rtrim($exception->getMessage(),": "));
            $this->session->addErrorCode($exception->getResponseCode());
			//return $vars;
        } catch (Exception) {
            $this->response = $exception->setWrapper($this->wrapper);
			//return $exception->getVariables();
        }
	}

	/** @inheritDoc */
	public function callbackLink(bool $chain = false):string|ReadableLocation
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