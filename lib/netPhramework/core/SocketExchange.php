<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\exceptions\ReadonlyException;
use netPhramework\locating\Location;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\networking\SmtpServer;
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
	public ExchangeEnvironment $environment;
	public CallbackManager $callbackManager;
	public Wrapper $wrapper;

	public Session $session {
		get { return $this->session; }
		set {
			if(isset($this->session))
				throw new ReadonlyException("Property is read-only");
		}
	}

	public FileManager $fileManager {
		get { return $this->fileManager; }
		set {
			if(isset($this->fileManager))
				throw new ReadonlyException("Property is read-only");
		}
	}

	public Location $location {
		get { return clone $this->location; }
		set {
			if(isset($this->location))
				throw new ReadonlyException("Property is read-only");
		}
	}

	public Variables $parameters {
		get { return clone $this->location->getParameters(); }
		set { throw new ReadonlyException("Property is read-only"); }
	}

	public MutablePath $path {
		get { return clone $this->location->getPath(); }
		set { throw new ReadonlyException("Property is read-only"); }
	}

	public SmtpServer $smtpServer {
		get {
			if(!isset($this->smtpServer))
				$this->smtpServer = new SmtpServer($this->environment);
			return $this->smtpServer;
		}
		set { throw new ReadonlyException("Property is read-only"); }
	}

	public string $siteAddress {
		get { return $this->environment->siteAddress; }
		set { throw new ReadonlyException("Property is read-only"); }
	}

	public string $callbackKey {
		get { return $this->callbackManager->getCallbackKey(); }
		set { throw new ReadonlyException("Property is read-only"); }
	}

	public Response $response {
		get { return $this->response; }
		set { throw new ReadonlyException("Property is read-only"); }
	}


	/**
	 * @param Redirector $fallback
	 * @return Variables
	 * @throws PathException
	 */
	public function redirect(Redirector $fallback):Variables
	{
		$redirection = new Redirection($this->path);
		$callback    = $this->callbackManager
			->callbackRedirector($this->parameters);
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
            $this->session->addFeedbackMessage(
				rtrim($exception->getMessage(),": "));
            $this->session->setFeedbackCode($exception->getResponseCode());
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
}