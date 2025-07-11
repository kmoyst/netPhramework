<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\locating\Location;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\networking\SmtpServer;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;
use netPhramework\responding\FileTransfer;
use netPhramework\responding\Presentation;
use netPhramework\responding\Redirection;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class SocketExchange implements Exchange
{
	private(set) Variables $parameters {
		get { return clone $this->location->getParameters(); }
		set {}
	}

	private(set) MutablePath $path {
		get { return clone $this->location->getPath(); }
		set {}
	}

	public SmtpServer $smtpServer {
		get {
			if(!isset($this->smtpServer))
				$this->smtpServer = new SmtpServer($this->environment);
			return $this->smtpServer;
		}
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

	private(set) Response $response {
		get { return $this->response; }
		set (Response $response) { $this->response = $response; }
	}

	public readonly Session $session;
	public readonly FileManager $fileManager;
	public readonly ExchangeEnvironment $environment;
	public readonly CallbackManager $callbackManager;

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