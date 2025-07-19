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
	public readonly Responder $responder;

	public readonly Request $request;

	protected string $protocol {get{
		return $this->context->get('HTTPS') === 'on' ? 'https' : 'http';
	}}

	protected string $domain {get{
		return $this->context->get('HTTP_HOST');
	}}

	public readonly WebContext $context;

	public function __construct()
	{
		parent::__construct();
		$this->context = new WebContext();
		$this->request = new WebRequest($this->context->requestInput);
		$this->responder = new WebResponder()
			->setEncoder(new HtmlEncoder())
			->setTemplateFinder(new FileFinder())
			->setWrapper(new Wrapper())
			->setSiteAddress($this->siteAddress)
		;
	}

	public function configureResponder(Responder $responder):void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;
	}
}