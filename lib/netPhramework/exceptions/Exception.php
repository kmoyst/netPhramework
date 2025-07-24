<?php

namespace netPhramework\exceptions;

use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\ResponseCode;
use netPhramework\rendering\Wrappable;
use netPhramework\runtime\RuntimeMode;

class Exception extends \Exception implements Wrappable, Response
{
	protected string $friendlyMessage = "SERVER ERROR";
	protected readonly ResponseCode $responseCode;

	public RuntimeMode $runtimeMode;

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

	public function setRuntimeMode(RuntimeMode $runtimeMode): self
	{
		$this->runtimeMode = $runtimeMode;
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
		if($this->runtimeMode->isDevelopment())
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
		$responder->present($this, $this->responseCode);
	}
}