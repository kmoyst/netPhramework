<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\core\Runtime;
use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;

class Dispatcher
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

	public function dispatchRequest(Runtime $runtime, Handler $handler):self
	{
		try {
			$request = $runtime->request;
			$this->services->session->start();
			$this->response = new Gateway($this->application)
				->mapToRouter($request->isToModify)
				->route($request->location)
				->openExchange($this->services)
				->execute()
			;
			return $this;
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