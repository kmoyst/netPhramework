<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\MutablePath;
use netPhramework\rendering\Wrapper;

readonly class Socket
{
	private Component $root;
    private Wrapper $wrapper;

    public function __construct(Component $root, Wrapper $wrapper)
    {
        $this->root = $root;
        $this->wrapper = $wrapper;
    }

	/**
	 * @param MutablePath $path
	 * @param Variables $parameters
	 * @param RequestContext $context
	 * @return Response
	 * @throws \Exception
	 */
    public function processRequest(MutablePath    $path, Variables $parameters,
								   RequestContext $context):Response
	{
        try
		{
            $exchange  		 = new SocketExchange();
			$navigator 		 = new Navigator();
			$callbackManager = new CallbackManager(
				$context->getCallbackKey(), clone $path, clone $parameters)
			;
			$exchange
				->setPath($path)
            	->setParameters($parameters)
				->setSession($context->getSession())
				->setWrapper($this->wrapper)
				->setCallbackManager($callbackManager)
			;
			$navigator
				->setRoot($this->root)
				->setPath($path)
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