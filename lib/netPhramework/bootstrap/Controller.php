<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\core\Runtime;
use netPhramework\core\RuntimeContext;
use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidRoleInSession;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;
use netPhramework\user\Session;
use netPhramework\user\User;

class Controller
{
	private(set) Services $services;
	private(set) Application $application;
	private(set) Response $response;
	private(set) Responder $responder;

	public Session $session {get{return $this->services->session;}}
	public RuntimeContext $context {get{return $this->runtime->context;}}

	private Handler $handler;

	public function __construct
	(
		private Site $site,
		private Runtime $runtime,
	)
	{}

	public function run():void
	{
		$this->initialize();
		try
		{
			$this
				->preAuthentication()
				->postAuthentication()
			;
		}
		catch (\Exception $exception)
		{
			if($this->runtime->mode->isDevelopment())
				echo $exception->getMessage();
			else {
				$this->handler->logException($exception);
				echo 'SERVER ERROR'; }
		}
	}

	public function initialize():self
	{
		$this->handler = new Handler($this->runtime->mode);
		register_shutdown_function([$this->handler, 'shutdown']);
		set_error_handler([$this->handler, 'handleError']);
		set_exception_handler([$this->handler, 'handleException'])
		;
		return $this;
	}

	public function preAuthentication():self
	{
		$this
			->assembleServices()
			->startSession()
		;
		return $this;
	}

	public function authenticate(User $user):self
	{
		$this->session->login($user);
		return $this;
	}

	public function postAuthentication():self
	{
		$this
			->buildApplication()
			->dispatchRequest()
			->prepareResponder()
			->deliverResponse()
		;
		return $this;
	}




	public function assembleServices():self
	{
		$this->services = new Services()
			->setSiteAddress($this->runtime->siteAddress)
			->setSession($this->runtime->session)
			->setCallbackManager($this->runtime->callbackManager)
			->setFileManager($this->runtime->fileManager)
			->setSmtpServer($this->runtime->smtpServer)
		;
		return $this;
	}

	public function startSession():self
	{
		try {
			$this->services->session->start();
		} catch (InvalidRoleInSession $e) {
			/** User has been set to null in Session */
			$this->handler->logException($e);
		}
		return $this;
	}

	public function buildApplication():self
	{
		$this->application = $this->site->getApplication(
			$this->services->session, $this->runtime->context);
		return $this;
	}

	public function dispatchRequest():self
	{
		try {
			$request = $this->runtime->request;
			$this->services->session->start();
			$this->response = new Gateway($this->application)
				->mapToRouter($request->isToModify)
				->route($request->location)
				->openExchange($this->services)
				->execute()
			;
			return $this;
		} catch (NodeNotFound $exception) {
			$this->response = $exception->setRuntimeMode($this->runtime->mode);
		} catch (Exception $exception) {
			$this->handler->logException($exception);
			$this->response = $exception->setRuntimeMode($this->runtime->mode);
		}
		return $this;
	}

	public function prepareResponder():self
	{
		$this->responder = $this->runtime->responder;
		$this->responder->application = $this->application;
		$this->responder->services 	= $this->services;
		$this->runtime->configureResponder($this->responder);
		return $this;
	}

	public function deliverResponse():self
	{
		$this->response->deliver($this->responder);
		return $this;
	}
}