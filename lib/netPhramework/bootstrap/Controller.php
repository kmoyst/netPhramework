<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Site;
use netPhramework\core\Exception;

readonly class Controller
{
	public function __construct(private SiteContext $context) {}

	public function initialize():self
	{
		$handler = new Handler($this->context->environment->inDevelopment);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		return $this;
	}

	public function run():void
	{
		$site = new Site();
		$site->application = $this->context->getApplication();
		$site->application->configureResponder($this->context->responder);
		try {
			try {
				$this->context->interpreter
					->interpretRequest($site)
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