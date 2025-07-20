<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\core\Runtime;
use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;

class Builder
{
	private Application $application;
	private Services 	$services;
	private Response  	$response;

	public function assembleServices(Runtime $runtime):self
	{
		$this->services = new Services()
			->setSiteAddress($runtime->siteAddress)
			->setSession($runtime->session)
			->setCallbackManager($runtime->callbackManager)
			->setFileManager($runtime->fileManager)
			->setSmtpServer($runtime->smtpServer)
		;
		return $this;
	}

	public function buildApplication(Site $site, Runtime $runtime):self
	{
		$this->application = $site->getApplication(
				$this->services->session, $runtime->context);
		return $this;
	}

	public function submitRequest(Runtime $runtime, Handler $handler):self
	{
		try {
			$this->services->session->start();
			$this->response = new Dispatcher()
				->setApplication($this->application)
				->setServices($this->services)
				->dispatchRequest($runtime->request)
			;
		} catch (NodeNotFound $exception) {
			$this->response = $exception->setRuntimeMode($runtime->mode);
		} catch (Exception $exception) {
			$handler->logException($exception);
			$this->response = $exception->setRuntimeMode($runtime->mode);
		}
		return $this;
	}

	public function deliverResponse(Runtime $runtime, Responder $responder):self
	{
		$responder->application = $this->application;
		$responder->services 	= $this->services;
		$runtime->configureResponder($responder);
		$this->response->deliver($responder);
		return $this;
	}
}