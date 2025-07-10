<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public Session $session { get; }
	public Environment $environment { get; }
	public FileManager $fileManager { get; }
	public CallbackManager $callbackManager  { get; }
}