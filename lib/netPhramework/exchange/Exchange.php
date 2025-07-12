<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;
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
use netPhramework\routing\MutablePath;
use netPhramework\routing\Redirection;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\transferring\File;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\FileTransfer;
use netPhramework\transferring\SmtpServer;

class Exchange implements CallbackContext
{
	private(set) Variables $parameters {
		get { return clone $this->location->getParameters(); }
		set {}
	}

	private(set) MutablePath $path {
		get { return clone $this->location->getPath(); }
		set {}
	}

	private(set) string $siteAddress {
		get { return $this->environment->siteAddress; }
		set {}
	}

	private(set) string $callbackKey {
		get { return $this->callbackManager->callbackKey; }
		set {}
	}

	private(set) Response $response;

	public readonly Session $session;
	public readonly FileManager $fileManager;
	public readonly Environment $environment;
	public readonly CallbackManager $callbackManager;
	public readonly SmtpServer $smtpServer;

	public function __construct
	(
		public readonly Location $location,
		RequestContext $context
	)
	{
		$this->session 			= $context->session;
		$this->fileManager 		= $context->fileManager;
		$this->environment 		= $context->environment;
		$this->callbackManager 	= $context->callbackManager;
		$this->smtpServer		= $context->smtpServer;
	}

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
}