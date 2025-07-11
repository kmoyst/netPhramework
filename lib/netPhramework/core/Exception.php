<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;
use netPhramework\rendering\Wrappable;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class Exception extends \Exception implements Wrappable, Response
{
	protected string $friendlyMessage = "SERVER ERROR";
	protected readonly ResponseCode $responseCode;

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
		if($this->environment->inDevelopment)
		{
			$message = $this->message . $this->getTraceAsString();
		}
		else
		{
			$message = $this->friendlyMessage;
		}
		return $message;
    }

	public function deliver(Responder $responder): void
	{
		$responder->present($this, $this->responseCode);
	}
}