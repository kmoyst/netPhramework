<?php

namespace netPhramework\core;

use netPhramework\locating\Location;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\Response;

readonly class Socket
{
	private Resource $root;
    private Wrapper $wrapper;

    public function __construct(Resource $root, Wrapper $wrapper)
    {
        $this->root = $root;
        $this->wrapper = $wrapper;
    }

	/**
	 * @param Location $location
	 * @param RequestContext $context
	 * @return Response
	 */
    public function processRequest(
		Location $location, RequestContext $context):Response
	{
        try
		{
            $exchange  = new SocketExchange();
			$navigator = new Navigator()
			;
			$exchange->location 		= $location;
			$exchange->wrapper 			= $this->wrapper;
			$exchange->session 			= $context->session;
			$exchange->fileManager 		= $context->fileManager;
			$exchange->callbackManager 	= $context->callbackManager;
			$exchange->environment 		= $context->environment
			;
			$navigator
				->setRoot($this->root)
				->setPath($location->getPath())
				->navigate()
				->handleExchange($exchange)
            ;
			return $exchange->response;
		}
		catch (Exception $exception)
		{
			return $exception
				->setWrapper($this->wrapper)
				->setEnvironment($context->environment)
				;
		}
	}
}