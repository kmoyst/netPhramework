<?php

namespace netPhramework\exceptions;

use netPhramework\core\RuntimeMode;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\ResponseCode;
use netPhramework\rendering\Wrappable;

class Exception extends \Exception implements Wrappable, Response
{
	protected string $friendlyMessage = "SERVER ERROR";
	protected readonly ResponseCode $responseCode;

	public RuntimeMode $hostMode;

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

	public function setHostMode(RuntimeMode $hostMode): self
	{
		$this->hostMode = $hostMode;
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
		if($this->hostMode->isDevelopment())
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