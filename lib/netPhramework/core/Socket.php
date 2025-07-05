<?php

namespace netPhramework\core;

use netPhramework\locating\MutableLocation;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\Response;

readonly class Socket
{
	private Node $root;
    private Wrapper $wrapper;

    public function __construct(Node $root, Wrapper $wrapper)
    {
        $this->root = $root;
        $this->wrapper = $wrapper;
    }

	/**
	 * @param MutableLocation $location
	 * @param RequestContext $context
	 * @return Response
	 */
    public function processRequest(
		MutableLocation $location, RequestContext $context):Response
	{
        try
		{
            $exchange  = new SocketExchange();
			$navigator = new Navigator();
			$exchange
				->setLocation($location)
				->setSession($context->getSession())
				->setFileManager($context->getFileManager())
				->setWrapper($this->wrapper)
				->setCallbackManager($context->getCallbackManager())
			;
			$navigator
				->setRoot($this->root)
				->setPath($location->getPath())
				->navigate()
				->handleExchange($exchange)
            ;
			return $exchange->getResponse();
		}
		catch (Exception $exception)
		{
			return $exception
				->setWrapper($this->wrapper)
				->setEnvironment($context->getEnvironment())
				;
		}
	}
}