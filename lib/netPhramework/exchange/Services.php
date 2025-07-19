<?php

namespace netPhramework\exchange;

use netPhramework\user\Session;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

class Services
{
	public Session         $session;
	public FileManager     $fileManager;
	public CallbackManager $callbackManager;
	public SmtpServer      $smtpServer;

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