<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Runtime;
use netPhramework\core\Site;

readonly class Controller
{
	public function __construct
	(
		private Site $site,
		private Runtime $runtime,
	)
	{}

	public function run():void
	{
		$handler = new Handler($this->runtime->mode);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException'])
		;
		try
		{
			new Dispatcher()
				->assembleServices($this->runtime)
				->buildApplication($this->site, $this->runtime)
				->dispatchRequest($this->runtime, $handler)
				->deliverResponse($this->runtime, $this->runtime->responder)
				;
		}
		catch (\Exception $exception)
		{
			if($this->runtime->mode->isDevelopment())
			{
				echo $exception->getMessage();
			}
			else
			{
				$handler->logException($exception);
				echo 'SERVER ERROR';
			}
		}
	}
}