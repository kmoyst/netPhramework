<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\FileTransfer;
use netPhramework\responding\Presentation;
use netPhramework\responding\Redirection;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class SocketExchange implements Exchange
{
	private MutableLocation $location;
	private Session $session;
	private CallbackManager $callbackManager;
	private FileManager $fileManager;
    private Wrapper $wrapper;
	private Response $response;

	/**
	 * @param Redirector $fallback
	 * @return Variables
	 * @throws PathException
	 */
	public function redirect(Redirector $fallback):Variables
	{
		$redirection = new Redirection($this->getPath());
		$callback    = $this->callbackManager
			->callbackRedirector($this->getParameters());
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

	/** @inheritDoc  */
	public function transferFile(File $file):void
	{
		$this->response = new FileTransfer($file);
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

	/** @inheritDoc */
	public function callbackLink(bool $chain = false):string|Encodable
	{
		if($chain) return clone $this->location;
		return $this->callbackManager->callbackLink(clone $this->location);
	}

	/** @inheritDoc */
	public function getCallbackKey(): string
	{
		return $this->callbackManager->getCallbackKey();
	}

	/** @inheritDoc */
	public function getPath(): MutablePath
	{
		return clone $this->location->getPath();
	}

	/** @inheritDoc */
	public function getParameters(): Variables
	{
		return clone $this->location->getParameters();
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

	public function setLocation(MutableLocation $location): self
	{
		$this->location = $location;
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