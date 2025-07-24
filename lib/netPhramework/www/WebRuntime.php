<?php

namespace netPhramework\www;

use netPhramework\common\FileFinder;
use netPhramework\core\Runtime;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\user\Session;

class WebRuntime extends Runtime
{
	protected string $protocol {get{
		return $this->context->get('HTTPS') === 'on' ? 'https' : 'http';
	}}
	protected string $domain {get{
		return $this->context->get('HTTP_HOST');
	}}
	/**
	 * Generates new Request instance each time
	 *
	 * @var Request
	 */
	public Request $request{get{
		return new WebRequest(new WebRequestInput());
	}}

	protected(set) Responder $responder;

	public function __construct()
	{
		$this->responder = new WebResponder()
			->setEncoder(new WebEncoder())
			->setTemplateFinder(new FileFinder())
			->setWrapper(new Wrapper())
			;
		parent::__construct(
			new WebVariables(), new Session(),
			new FileManager(), new CallbackManager());
	}

	public function configureWrapper(Wrapper $wrapper):void
	{
		$wrapper->addStyleSheet('framework-stylesheet');
	}

	public function configureTemplateFinder(FileFinder $finder): void
	{
		$finder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('phtml')
			->extension('tpl')
			->extension('css')
		;
	}
}