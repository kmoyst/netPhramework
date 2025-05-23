<?php

namespace netPhramework\exceptions;

use netPhramework\bootstrap\Environment;
use netPhramework\common\Variables;
use netPhramework\core\Responder;
use netPhramework\core\Response;
use netPhramework\core\ResponseCode;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\Message;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;

class Exception extends \Exception implements Response, Viewable, Wrappable
{
	protected string $friendlyMessage = "SERVER ERROR";
    protected readonly ResponseCode $responseCode;
    private Wrapper $wrapper;
	private Environment $environment;
	//private Variables $variables;

    public function __construct(
		string $message = "", ?ResponseCode $responseCode = null)
    {
        $this->responseCode = $responseCode ?? ResponseCode::SERVER_ERROR;
		//	$this->variables = new Variables();
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

    public function deliver(Responder $responder): void
    {
		if(isset($this->environment) && $this->environment->inDevelopment())
		{
			$content = $this;
		}
		else
		{
			$content = new Message($this->friendlyMessage)->setTitle('Error');
		}
		$responder->display(
			$this->wrapper->wrap($content), $this->responseCode);
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

    public function getContent(): self
    {
        return $this;
    }
}