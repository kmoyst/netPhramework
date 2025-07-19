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
use netPhramework\exchange\Services;

readonly class Controller
{
	private Application $application;
	private Request $request;
	private Services $services;
	private Responder $responder;
	private RuntimeContext $runtimeContext;

	private string $siteAddress;
	private RuntimeMode $runtimeMode;

	public function __construct(private Site $site, private Runtime $runtime) {}

	public function run():void
	{
		$this
			->initializeHandlers()
			->assembleServices()
			->prepareController()
			->prepareSite()
			->prepareApplication()
			->prepareResponder()
			->processExchange();
	}

	private function initializeHandlers():self
	{
		$handler = new Handler($this->runtime->mode);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	private function assembleServices():self
	{
		$this->services = new Services()
			->setSession($this->runtime->session)
			->setSmtpServer($this->runtime->smtpServer)
			->setCallbackManager($this->runtime->callbackManager)
			->setFileManager($this->runtime->fileManager)
		;
		return $this;
	}

	private function prepareController():self
	{
		$this->request 	   		= $this->runtime->request;
		$this->responder   		= $this->runtime->responder;
		$this->siteAddress 		= $this->runtime->siteAddress;
		$this->runtimeContext	= $this->runtime->context;
		$this->runtimeMode		= $this->runtime->mode;
		return $this;
	}

	private function prepareSite():self
	{
		$this->site->runtimeContext = $this->runtimeContext;
		$this->site->session	    = $this->services->session;
		return $this;
	}

	private function prepareApplication():self
	{
		$this->application = $this->site->application;
		return $this;
	}

	private function prepareResponder():self
	{
		$this->responder->application 	= $this->application;
		$this->responder->siteAddress 	= $this->siteAddress;
		$this->responder->services 		= $this->services;
		$this->runtime->configureResponder($this->responder);
		return $this;
	}

	private function processExchange():void
	{
		try {
			try {
				$this->services->session->start();
				new Gateway($this->application)
					->mapToRouter($this->request->isToModify)
					->route($this->request->location)
					->openExchange($this->services)
					->execute($this->siteAddress)
					->deliver($this->responder);
			} catch (NodeNotFound $exception) {
				$exception
					->setRuntimeMode($this->runtimeMode)
					->deliver($this->responder);
			} catch (Exception $exception) {
				$exception
					->setRuntimeMode($this->runtimeMode)
					->deliver($this->responder);
				$this->logException($exception);
			}
		}
		catch (\Exception $exception)
		{
			if($this->runtimeMode->isDevelopment())
			{
				echo $exception->getMessage();
			}
			else
			{
				$this->logException($exception);
				echo 'SERVER ERROR';
			}
		}
    }

	private function logException(\Exception $exception):void
	{
		ob_start();
		echo $exception->getCode();
		echo ": ";
		echo $exception->getMessage();
		error_log('Error:'. ob_get_clean());
	}
}