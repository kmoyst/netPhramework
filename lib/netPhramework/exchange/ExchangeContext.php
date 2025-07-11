<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\networking\SmtpServer;
use netPhramework\responding\FileManager;

interface ExchangeContext
{
	public ExchangeEnvironment $environment { get; }
	public CallbackManager $callbackManager  { get; }
	public Session $session {get;}
	public FileManager $fileManager {get;}
	public SmtpServer $smtpServer{get;}
}