<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\locating\Location;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\presentation\HiddenInput;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\View;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\FileTransfer;
use netPhramework\responding\Presentation;
use netPhramework\responding\Redirection;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class SocketExchange implements Exchange
{
    /**
     * The request Path. Protected from mutability through cloning.
     *
     * @var MutablePath
     */
	private MutablePath $path;
    /**
     * The request Parameters. Protected from mutability through cloning.
     *
     * @var Variables
     */
	private Variables $parameters;
	private Session $session;
	private CallbackManager $callbackManager;
	private FileManager $fileManager;
    private Wrapper $wrapper;
	private Response $response;

	/** @inheritdoc  */
	public function redirect(Redirector $fallback):Variables
	{
		$redirection = new Redirection(clone $this->path);
		$callback = $this->callbackManager->callbackRedirector();
		($callback ?? $fallback)->redirect($redirection);
		$this->response = $redirection;
		return $redirection->getParameters();
	}

	/** @inheritDoc */
	public function ok(View $view):ConfigurableView
	{
		return $this->display($view, ResponseCode::OK);
	}

    /** @inheritDoc */
	public function display(View $view, ResponseCode $code):ConfigurableView
	{
		$this->response = new Presentation()
			->setContent($this->wrapper->wrap($view))
			->setCode($code)
		;
		return $view;
	}

	/** @inheritDoc */
	public function error(Exception $exception, Redirector $fallback): void
	{
        try {
			$this->redirect($fallback);
            $this->session->addErrorMessage(
				rtrim($exception->getMessage(),": "));
            $this->session->addErrorCode($exception->getResponseCode());
        } catch (Exception) {
			$this->response = $exception->setWrapper($this->wrapper);
        }
	}

	public function transferFile(File $file):void
	{
		$this->response = new FileTransfer($file);
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
	public function getPath(): MutablePath
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

	/** @inheritdoc  */
	public function getFileManager(): FileManager
	{
		return $this->fileManager;
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

	/**
	 * Sets the Request Path (usually by Socket)
	 *
	 * @param MutablePath $path
	 * @return $this
	 */
	public function setPath(MutablePath $path): SocketExchange
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
	 * Injector for Upload Manager
	 *
	 * @param FileManager $fileManager
	 * @return $this
	 */
	public function setFileManager(FileManager $fileManager): self
	{
		$this->fileManager = $fileManager;
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