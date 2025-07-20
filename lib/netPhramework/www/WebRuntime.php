<?php

namespace netPhramework\www;

use netPhramework\common\FileFinder;
use netPhramework\core\Runtime;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\HtmlEncoder;
use netPhramework\rendering\Wrapper;

class WebRuntime extends Runtime
{
	public Responder $responder{get{
		return new WebResponder()
			->setEncoder(new HtmlEncoder())
			->setTemplateFinder(new FileFinder())
			->setWrapper(new Wrapper())
		;
	}}

	public Request $request{get{
		return new WebRequest($this->context->requestInput);
	}}

	protected string $protocol {get{
		return $this->context->get('HTTPS') === 'on' ? 'https' : 'http';
	}}

	protected string $domain {get{
		return $this->context->get('HTTP_HOST');
	}}

	public function __construct(
		public readonly WebRuntimeContext $context = new WebRuntimeContext())
	{
		parent::__construct();
	}

	public function configureResponder(Responder $responder):void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('phtml')
			->extension('tpl')
			->extension('css')
		;
	}
}