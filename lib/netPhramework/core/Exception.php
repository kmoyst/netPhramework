<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;

class Exception extends \Exception
	implements Wrappable, Response
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

	public function deliver(Responder $responder): void
	{
		$responder->present($this->wrapper->wrap($this), $this->code);
	}
}