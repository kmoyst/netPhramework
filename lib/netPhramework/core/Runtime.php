<?php

namespace netPhramework\core;

use netPhramework\common\FileFinder;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;
use netPhramework\user\Session;

abstract class Runtime
{
	abstract public RuntimeContext $context {get;}
	abstract public Request $request {get;}
	abstract public Responder $responder {get;}
	abstract protected string $protocol {get;}
	abstract protected string $domain {get;}

	public string $siteAddress {get{
		return "$this->protocol://$this->domain";
	}}

	public RuntimeMode $mode {get{
		$mode = $this->context->get(RuntimeKey::HOST_MODE->value);
		return RuntimeMode::tryFrom($mode) ?? RuntimeMode::PRODUCTION;
	}}

	private(set) SmtpServer $smtpServer {get{
		if(!isset($this->smtpServer)) {
			$address = $this->context
				->get(RuntimeKey::SMTP_SERVER_ADDRESS->value);
			$name = $this->context
				->get(RuntimeKey::SMTP_SERVER_NAME->value);
			$this->smtpServer = new SmtpServer($address, $name);
		}
		return $this->smtpServer;
	}}

	public function __construct
	(
	private(set) readonly Session $session,
	private(set) readonly FileManager $fileManager,
	private(set) readonly CallbackManager $callbackManager,
	)
	{
	}

	public function configureWrapper(Wrapper $wrapper):void {}
	abstract public function configureTemplateFinder(FileFinder $finder):void;
}