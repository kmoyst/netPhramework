<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;

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
		$this->site->configureResponder($this->site->responder);
		return $this;
	}

	private function exchange():void
	{
		try {
			try {
				$this->site->requestProcessor
					->interpret($this->site->environment)
					->process($this->site)
					->deliver($this->site->responder);
			} catch (Exception $exception) {
				$exception
					->setEnvironment($this->site->environment)
					->deliver($this->site->responder);
				return;
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
				ob_start();
				print_r($exception);
				error_log(ob_get_clean());
				echo 'SERVER ERROR';
			}
		}
    }
}