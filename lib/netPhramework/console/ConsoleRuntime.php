<?php

namespace netPhramework\console;

use netPhramework\common\FileFinder;
use netPhramework\core\Runtime;
use netPhramework\core\RuntimeKey;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrapper;

class ConsoleRuntime extends Runtime
{
	public Request $request {get{
		return new ConsoleRequest();
	}}

	public Responder $responder{get{
		return new ConsoleResponder()
			->setEncoder(new Encoder())
			->setTemplateFinder(new FileFinder())
			->setWrapper(new Wrapper())
		;
	}}

	public ConsoleRuntimeContext $context{get{
		return new ConsoleRuntimeContext()->initialize();
	}set{}}

	protected string $domain {get{
		return $this->context->get(RuntimeKey::DOMAIN->value);
	}}

	protected string $protocol = 'cli';

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