<?php

namespace netPhramework\exchange;

use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\PathException;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Presentation;
use netPhramework\rendering\View;
use netPhramework\routing\CallbackContext;
use netPhramework\routing\CallbackManager;
use netPhramework\routing\Location;
use netPhramework\routing\Path;
use netPhramework\routing\Redirection;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\transferring\File;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\FileTransfer;
use netPhramework\transferring\SmtpServer;
use netPhramework\user\Session;

class Exchange implements CallbackContext
{
	private(set) Response $response;
	private(set) Session $session;
	private(set) FileManager $fileManager;
	private(set) CallbackManager $callbackManager;
	private(set) SmtpServer $smtpServer;

	public Variables $parameters {get{
		return clone $this->location->getParameters();
	}}

	public Path $path {get{
		return clone $this->location->path;
	}}

	public string $siteAddress;

	public string $callbackKey {get{
		return $this->callbackManager->callbackKey;
	}}

	public function __construct
	(
		private(set) Location $location,
	)
	{}

	/**
	 * @param Redirector $fallback
	 * @return Variables
	 * @throws PathException
	 */
	public function redirect(Redirector $fallback):Variables
	{
		$redirection = new Redirection($this->path);
		$callback    = $this->callbackManager->getRedirector($this->parameters);
		($callback ?? $fallback)->redirect($redirection);
		$this->response = $redirection;
		return $redirection->getParameters();
	}

	/**
	 * Generate a standard displayable Response to be wrapped.
	 *
	 * @param View $view
	 * @return ConfigurableView - Passes back the content for additional config
	 */
	public function ok(View $view):ConfigurableView
	{
		return $this->display($view, ResponseCode::OK);
	}

	/**
	 * Centralized method for wrapping wrappable content a passing to
	 * final display method.
	 *
	 * @param View $view
	 * @param ResponseCode $code
	 * @return ConfigurableView - Passes back the content for additional config
	 */
	public function display(View $view, ResponseCode $code):ConfigurableView
	{
		$this->response = new Presentation($view, $code);
		return $view;
	}

	/**
	 * Respond with file transfer
	 *
	 * @param File $file
	 * @return void
	 */
	public function transferFile(File $file):void
	{
		$this->response = new FileTransfer($file);
	}

	/**
	 * Dispatches with an error code and message stored in Session for display
	 *
	 * @param Exception $exception
	 * @param Redirector $fallback
	 * @return void
	 */
	public function error(Exception $exception, Redirector $fallback): void
	{
        try {
			$this->redirect($fallback);
            $this->session->addFeedbackMessage(
				rtrim($exception->getMessage(),": "));
            $this->session->setFeedbackCode($exception->getResponseCode());
        } catch (Exception) {
			$this->response = $exception;
        }
	}

	/**
	 * @param bool $chain
	 * @return string|Encodable
	 */
	public function callbackLink(bool $chain = false):string|Encodable
	{
		if($chain) return clone $this->location;
		return $this->callbackManager->getLink(clone $this->location);
	}

	public function setLocation(Location $location): self
	{
		$this->location = $location;
		return $this;
	}

	public function setCallbackManager(CallbackManager $callbackManager): self
	{
		$this->callbackManager = $callbackManager;
		return $this;
	}

	public function setFileManager(FileManager $fileManager): self
	{
		$this->fileManager = $fileManager;
		return $this;
	}

	public function setSmtpServer(SmtpServer $smtpServer): self
	{
		$this->smtpServer = $smtpServer;
		return $this;
	}

	public function setSession(Session $session): self
	{
		$this->session = $session;
		return $this;
	}


}