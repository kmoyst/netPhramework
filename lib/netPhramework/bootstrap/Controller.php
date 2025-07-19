<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
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

	public function __construct(private Site $site) {}

	public function run():void
	{
		$this->initialize()->prepare()->exchange();
	}

	private function initialize():self
	{
		$handler = new Handler($this->site->runtime->mode);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	private function prepare():self
	{
		$this->application = $this->site->application;
		$this->request = $this->site->runtime->request;
		$this->services = $this->site->services;
		$this->responder = $this->site->runtime->responder;
		$this->site->runtime->configureResponder($this->responder);
		return $this;
	}

	private function exchange():void
	{
		try {
			try {
				$this->site->runtime->session->start();
				new Gateway($this->application)
					->mapToRouter($this->site->runtime->request->isToModify)
					->route($this->site->runtime->request->location)
					->openExchange($this->site->services)
					->execute($this->site->address)
					->deliver($this->site->runtime->responder);
			} catch (NodeNotFound $exception) {
				$exception
					->setHostMode($this->site->runtime->mode)
					->deliver($this->site->runtime->responder);
			} catch (Exception $exception) {
				$exception
					->setHostMode($this->site->runtime->mode)
					->deliver($this->site->runtime->responder);
				$this->logException($exception);
			}
		}
		catch (\Exception $exception)
		{
			if($this->site->inDevelopment)
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