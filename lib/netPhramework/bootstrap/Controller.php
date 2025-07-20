<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Runtime;
use netPhramework\core\Site;

readonly class Controller
{
	private Processor $processor;

	public function __construct(private Site $site, private Runtime $runtime)
	{
		$this->processor = new Processor($this->runtime, $this->site);
	}

	public function run():void
	{
		try
		{
			$this->processor
				->initializeHandlers()
				->assembleServices()
				->prepareForRequest()
				->prepareResponder()
				->processExchange()
				->deliverResponse();
		}
		catch (\Exception $exception)
		{
			if($this->runtime->mode->isDevelopment())
			{
				echo $exception->getMessage();
			}
			else
			{
				$this->processor->logException($exception);
				echo 'SERVER ERROR';
			}
		}
	}
}