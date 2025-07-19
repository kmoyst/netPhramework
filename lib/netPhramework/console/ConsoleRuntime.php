<?php

namespace netPhramework\console;

use netPhramework\common\FileFinder;
use netPhramework\core\Runtime;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrapper;

class ConsoleRuntime extends Runtime
{
	public Request $request {get{
		return new ConsoleRequest();
	}}

	public readonly Responder $responder;

	public readonly ConsoleContext $context;

	protected readonly string $domain;

	protected readonly string $protocol;

	public function __construct()
	{
		$this->context = new ConsoleContext();
		$this->responder = new ConsoleResponder()
			->setEncoder(new Encoder())
			->setTemplateFinder(new FileFinder())
			->setWrapper(new Wrapper())
			->setSiteAddress($this->siteAddress)
		;
		$this->protocol = 'cli';
		$this->domain = '';
	}

	public function configureResponder(Responder $responder): void
	{
		$responder->templateFinder
			->directory('../template/plain')
			->directory(__DIR__ . '/../../../templates/plain')
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
		;
	}
}