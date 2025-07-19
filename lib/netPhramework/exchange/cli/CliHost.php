<?php

namespace netPhramework\exchange\cli;

use netPhramework\common\Variables;
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

class CliHost implements Host
{
	public HostContext $host {get{
		$variables = new Variables();
		$dotenv = fopen('dotenv', 'r');
		while($line = fgets($dotenv))
		{
			preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
			$variables->add($m[1], $m[2]);
		}
		fclose($dotenv);
		$variables->add(HostKey::HOST_PROTOCOL->value, 'CLI');
		return new HostContext(new CliHostVariables($variables));
	}}

	private(set) Request $request {get{
		if(!isset($this->request))
			$this->request = new CliRequest();
		return $this->request;
	}}

	public Responder $responder{get{
		return new CliResponder();
	}}

	public Services $services{get{
		return new Services(
			new Session(),
			new FileManager(),
			new CallbackManager(),
			new SmtpServer(
				$this->host->get(HostKey::SMTP_SERVER_ADDRESS),
				$this->host->get(HostKey::SMTP_SERVER_NAME)
			)
		);
	}}

	public string $hostDomain {get{
		return $this->info->get(HostKey::HOST_DOMAIN->value);
	}}

	public HostMode $mode {get{
		$mode = $this->info->get(HostKey::HOST_MODE->value);
		return HostMode::tryFrom($mode) ?? HostMode::PRODUCTION;
	}}

	public string $smtpServerAddress {get{
		return $this->info->get(HostKey::SMTP_SERVER_ADDRESS->value);
	}}

	public string $smtpServerName {get{
		return $this->info->get(HostKey::SMTP_SERVER_NAME->value);
	}}

	public function configureResponder(Responder $responder):void
	{
		$responder->templateFinder
			->directory('../templates/plain')
			->directory(__DIR__ . '/../../../templates/plain')
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;
	}
}