<?php

namespace netPhramework\exchange;

use netPhramework\user\Session;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

class Services
{
	private(set) string			 $siteAddress;
	private(set) Session         $session;
	private(set) FileManager     $fileManager;
	private(set) CallbackManager $callbackManager;
	private(set) SmtpServer      $smtpServer;

	public function setSiteAddress(string $siteAddress): self
	{
		$this->siteAddress = $siteAddress;
		return $this;
	}

	public function setSession(Session $session): self
	{
		$this->session = $session;
		return $this;
	}

	public function setFileManager(FileManager $fileManager): self
	{
		$this->fileManager = $fileManager;
		return $this;
	}

	public function setCallbackManager(CallbackManager $callbackManager): self
	{
		$this->callbackManager = $callbackManager;
		return $this;
	}

	public function setSmtpServer(SmtpServer $smtpServer): self
	{
		$this->smtpServer = $smtpServer;
		return $this;
	}
}