<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Request;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;

class Dispatcher
{
	private Application $application;
	private Services $services;

	public function setApplication(Application $application): self
	{
		$this->application = $application;
		return $this;
	}

	public function setServices(Services $services): self
	{
		$this->services = $services;
		return $this;
	}

	/**
	 * @param Request $request
	 * @return Response
	 * @throws Exception
	 * @throws NodeNotFound
	 */
	public function dispatchRequest(Request $request):Response
	{
		return new Gateway($this->application)
			->mapToRouter($request->isToModify)
			->route($request->location)
			->openExchange($this->services)
			->execute()
		;
	}
}