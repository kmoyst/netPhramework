<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public Environment $environment { get; }
	public CallbackManager $callbackManager  { get; }
	public Session $session {get;}
	public FileManager $fileManager {get;}
}