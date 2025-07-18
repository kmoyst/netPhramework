<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;

readonly class Controller
{
	public function __construct(private Site $site) {}

	public function run():void
	{
		$this->initialize()->configure()->exchange();
	}

	private function initialize():self
	{
		$handler = new Handler($this->site->environment->inDevelopment);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	private function configure():self
	{
		$this->site->configure();
		return $this;
	}

	private function exchange():void
	{
		try {
			try {
				$this->site->services->session->start();
				new Gateway($this->site->application)
					->mapToRouter($this->site->request->isModificationRequest)
					->route($this->site->request->location)
					->openExchange($this->site->services)
					->dispatch($this->site->environment)
					->deliver($this->site->responder);
			} catch (NodeNotFound $exception) {
				$exception
					->setEnvironment($this->site->environment)
					->deliver($this->site->responder);
			} catch (Exception $exception) {
				$exception
					->setEnvironment($this->site->environment)
					->deliver($this->site->responder);
				$this->logException($exception);
			}
		}
		catch (\Exception $exception)
		{
			if($this->site->environment->inDevelopment)
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