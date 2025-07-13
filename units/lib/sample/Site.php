<?php

namespace sample;
use netPhramework\exchange\Responder;

class Site extends \stubs\Site
{
	public function configureResponder(Responder $responder): void
	{
		$responder->templateFinder
			->extension('tpl')
			->directory(__DIR__.'/../../../templates/plain')
		;
		parent::configureResponder($responder);
	}
}