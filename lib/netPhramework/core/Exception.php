<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\responding\Relayer;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseContent;
use netPhramework\responding\ResponseCode;
use netPhramework\responding\ResponseFactory;

class Exception extends \Exception
	implements ResponseContent, Wrappable, ResponseFactory
{
	protected string $friendlyMessage = "SERVER ERROR";
    protected readonly ResponseCode $responseCode;
    private Wrapper $wrapper;
	private Environment $environment;

    public function __construct(
		string $message = "", ?ResponseCode $responseCode = null)
    {
        $this->responseCode = $responseCode ?? ResponseCode::SERVER_ERROR;
        parent::__construct($message, $this->responseCode->value);
    }

    public function getResponseCode(): ResponseCode
    {
        return $this->responseCode;
    }

    public function setWrapper(Wrapper $wrapper): Exception
    {
        $this->wrapper = $wrapper;
        return $this;
    }

	public function setEnvironment(Environment $environment): Exception
	{
		$this->environment = $environment;
		return $this;
	}

	/** @inheritDoc */
    public function getTitle(): string
    {
        return "ERROR";
    }

	/**
	 * Used by wrapper
	 *
	 * @return string
	 */
    public function getContent(): string
    {
		if(isset($this->environment) && $this->environment->inDevelopment())
		{
			$message = $this->message;
		}
		else
		{
			$message = $this->friendlyMessage;
		}
		return $message;
    }

	/**
	 * Used by Response
	 *
	 * @param Encoder $encoder
	 * @return string
	 */
	public function encode(Encoder $encoder): string
	{
		return $this->wrapper->encode($encoder);
	}

	public function chooseRelay(Responder $responder): Relayer
	{
		return $responder->getDisplayer();
	}

	public function getResponse():Response
	{
		return new Response()
			->setContent($this)
			->setCode($this->responseCode)
			;
	}
}