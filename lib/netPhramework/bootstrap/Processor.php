<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\core\Runtime;
use netPhramework\core\RuntimeContext;
use netPhramework\core\RuntimeMode;
use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;

class Processor
{
	private Services $services;
	private Request $request;
	private RuntimeContext $runtimeContext;
	private Application $application;

	private string $siteAddress;
	private RuntimeMode $runtimeMode;

	private Responder $responder;
	private Response $response;

	public function __construct
	(
		private readonly Runtime $runtime,
		private readonly Site $site
	)
	{}

	public function initializeHandlers():self
	{
		$handler = new Handler($this->runtime->mode);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	public function assembleServices():self
	{
		$this->services = new Services()
			->setSession($this->runtime->session)
			->setSmtpServer($this->runtime->smtpServer)
			->setCallbackManager($this->runtime->callbackManager)
			->setFileManager($this->runtime->fileManager)
		;
		return $this;
	}

	public function prepareForRequest():self
	{
		$this->request 	   		= $this->runtime->request;
		$this->siteAddress 		= $this->runtime->siteAddress;
		$this->runtimeContext	= $this->runtime->context;
		$this->runtimeMode		= $this->runtime->mode;
		$this->application	    = $this->site->getApplication(
			$this->services->session, $this->runtimeContext);
		return $this;
	}

	public function prepareResponder():self
	{
		$this->responder				= $this->runtime->responder;
		$this->responder->application 	= $this->application;
		$this->responder->siteAddress 	= $this->siteAddress;
		$this->responder->services 		= $this->services;
		$this->runtime->configureResponder($this->responder);
		return $this;
	}

	public function processExchange():self
	{
		try {
			$this->services->session->start();
			$this->response = new Gateway($this->application)
				->mapToRouter($this->request->isToModify)
				->route($this->request->location)
				->openExchange($this->services)
				->execute($this->siteAddress);
		} catch (NodeNotFound $e) {
			$this->response = $e->setRuntimeMode($this->runtimeMode);
		} catch (Exception $e) {
			$this->response = $e->setRuntimeMode($this->runtimeMode);
			$this->logException($e);
		} finally {
			return $this;
		}
	}

	public function deliverResponse():self
	{
		$this->response->deliver($this->responder);
		return $this;
	}

	public function logException(\Exception $exception):void
	{
		ob_start();
		echo $exception->getCode();
		echo ": ";
		echo $exception->getMessage();
		error_log('Error:'. ob_get_clean());
	}
}