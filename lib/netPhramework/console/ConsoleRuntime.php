<?php

namespace netPhramework\console;

use netPhramework\common\FileFinder;
use netPhramework\core\Runtime;
use netPhramework\core\RuntimeContext;
use netPhramework\core\RuntimeKey;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\user\Session;

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

	protected string $domain {get{
		return $this->context->get(RuntimeKey::DOMAIN->value);
	}}

	protected string $protocol = 'cli';

	public function __construct
	(
	Session $session = new Session(),
	private(set) readonly RuntimeContext $context = new ConsoleRuntimeContext()

	)
	{
		parent::__construct($session, new FileManager(), new CallbackManager());
	}

	public function configureResponder(Responder $responder): void
	{
		$responder->templateFinder
			->directory('../templates/plain')
			->directory('../html/plain')
			->directory(__DIR__ . '/../../../templates/plain')
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->directory(__DIR__ . '/../../../html')
			->extension('phtml')
			->extension('tpl')
		;
	}
}