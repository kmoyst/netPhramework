<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\PathException;
use netPhramework\locating\Location;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\networking\SmtpServer;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;
use netPhramework\responding\File;
use netPhramework\responding\FileManager;
use netPhramework\responding\FileTransfer;
use netPhramework\responding\Presentation;
use netPhramework\responding\Redirection;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class RequestExchange implements Exchange
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
	public readonly ExchangeEnvironment $environment;
	public readonly CallbackManager $callbackManager;
	public readonly SmtpServer $smtpServer;

	public function __construct
	(
		public readonly Location $location,
		ExchangeContext $context
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

	/** @inheritDoc */
	public function ok(View $view):ConfigurableView
	{
		return $this->display($view, ResponseCode::OK);
	}

    /** @inheritDoc */
	public function display(View $view, ResponseCode $code):ConfigurableView
	{
		$this->response = new Presentation($view, $code);
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
            $this->session->addFeedbackMessage(
				rtrim($exception->getMessage(),": "));
            $this->session->setFeedbackCode($exception->getResponseCode());
        } catch (Exception) {
			$this->response = $exception;
        }
	}

	/** @inheritDoc */
	public function callbackLink(bool $chain = false):string|Encodable
	{
		if($chain) return clone $this->location;
		return $this->callbackManager->getLink(clone $this->location);
	}
}