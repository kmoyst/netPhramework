<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Request;
use netPhramework\exchange\Response;
use netPhramework\exchange\Services;

readonly class Dispatcher
{
	public function __construct
	(
		private Application $application,
		private Services $services,
	)
	{}
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