<?php

namespace netPhramework\console;

use netPhramework\common\FileFinder;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\CallbackManager;
use netPhramework\runtime\Runtime;
use netPhramework\runtime\RuntimeKey;
use netPhramework\runtime\Session;
use netPhramework\transferring\FileManager;

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

	)
	{
		parent::__construct(
			new ConsoleVariables(), $session,
			new FileManager(), new CallbackManager());
	}

	public function configureTemplateFinder(FileFinder $finder): void
	{
		$finder
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