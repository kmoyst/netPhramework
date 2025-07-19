<?php

namespace netPhramework\bootstrap;

use netPhramework\configuration\Site;
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
		$handler = new Handler($this->site->hostMode);
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
					->mapToRouter($this->site->request->isForModification)
					->route($this->site->request->location)
					->openExchange($this->site->services)
					->execute($this->site->host)
					->deliver($this->site->responder);
			} catch (NodeNotFound $exception) {
				$exception
					->setHostMode($this->site->hostMode)
					->deliver($this->site->responder);
			} catch (Exception $exception) {
				$exception
					->setHostMode($this->site->hostMode)
					->deliver($this->site->responder);
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