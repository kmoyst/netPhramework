<?php

namespace netPhramework\exceptions;

use netPhramework\bootstrap\Environment;
use netPhramework\rendering\Wrapper;
use netPhramework\rendering\Message;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class Exception extends \Exception implements Response, Wrappable, Viewable
{
	protected string $friendlyMessage = "SERVER ERROR";
    protected ResponseCode $responseCode;
    private Wrapper $wrapper;
	private Environment $environment;

    public function __construct(
		string $message = "",
		ResponseCode $responseCode = ResponseCode::SERVER_ERROR)
    {
        parent::__construct($message, $responseCode->value);
		$this->responseCode = $responseCode;
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

    public function deliver(Responder $responder): void
    {
		if(isset($this->environment) && $this->environment->inDevelopment())
		{
			$content = $this;
		}
		else
		{
			$content = (new Message($this->friendlyMessage))->setTitle('Error');
		}
		$responder->display(
			$this->wrapper->wrap($content),
			$this->responseCode);
    }

    public function getTemplateName(): string
    {
        return 'message';
    }

    public function getVariables(): iterable
    {
        return ['message' => $this->message];
    }

    public function getTitle(): string
    {
        return "ERROR";
    }

    public function getContent(): Viewable
    {
        return $this;
    }
}