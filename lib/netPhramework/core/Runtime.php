<?php

namespace netPhramework\core;

use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
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

	public Session $session {get{
		return new Session();
	}}
	public FileManager $fileManager {get{
		return new FileManager();
	}}
	public CallbackManager $callbackManager{get{
		return new CallbackManager();
	}}

	public SmtpServer $smtpServer {get{
		$address = $this->context
			->get(RuntimeKey::SMTP_SERVER_ADDRESS->value);
		$name = $this->context
			->get(RuntimeKey::SMTP_SERVER_NAME->value);
		return new SmtpServer($address, $name);
	}}

	abstract public function configureResponder(Responder $responder):void;
}