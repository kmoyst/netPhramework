<?php

namespace netPhramework\exchange\http;

use netPhramework\exchange\Host;
use netPhramework\exchange\host\HostContext;
use netPhramework\exchange\host\HostKey;
use netPhramework\exchange\host\HostMode;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;
use netPhramework\user\Session;

class WebHost implements Host
{
	public readonly Responder $responder;
	public readonly Session $session;
	public readonly FileManager $fileManager;
	public readonly CallbackManager $callbackManager;

	private function retrieve(string $key):?string
	{
		return filter_input(INPUT_SERVER, $key);
	}

	public HostMode $mode {get{
		$mode = $this->retrieve(HostKey::HOST_MODE->value);
		return HostMode::tryFrom($mode) ?? HostMode::PRODUCTION;
	}}

	public Request $request {get{
		return new HttpRequest(new HttpInput());
	}}

	private(set) SmtpServer $smtpServer {get{
		if(!isset($this->smtpServer))
		{
			$address = $this->retrieve(HostKey::SMTP_SERVER_ADDRESS->value);
			$name = $this->retrieve(HostKey::SMTP_SERVER_NAME->value);
			$this->smtpServer = new SmtpServer($address, $name);
		}
		return $this->smtpServer;
	}}

	public string $domain {get{
		return $this->retrieve(HostKey::HOST_DOMAIN->value);
	}}

	public string $protocol {get{
		return $this->retrieve('HTTPS') === 'on' ? 'https' : 'http';
	}}

	public function configureResponder(Responder $responder): void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css');
	}
}