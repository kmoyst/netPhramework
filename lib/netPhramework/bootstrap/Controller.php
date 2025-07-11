<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Context;
use netPhramework\exceptions\Exception;

readonly class Controller
{
	public function __construct(private Context $context) {}

	public function run():void
	{
		$this->initialize()->configure()->exchange();
	}

	private function initialize():self
	{
		$handler = new Handler($this->context->environment->inDevelopment);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	private function configure():self
	{
		$this->context->configureResponder($this->context->responder);
		return $this;
	}

	private function exchange():void
	{
		try {
			try {
				$this->context->interpreter
					->interpretRequest($this->context->site)
					->process($this->context)
					->deliver($this->context->responder);
			} catch (Exception $exception) {
				$exception
					->setEnvironment($this->context->environment)
					->deliver($this->context->responder);
				return;
			}
		}
		catch (\Exception $exception)
		{
			if($this->context->environment->inDevelopment)
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