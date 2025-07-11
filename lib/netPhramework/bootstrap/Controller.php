<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Site;
use netPhramework\core\Exception;

class Controller
{
	private bool $fatalErrorHandled = false;

	public function __construct(private readonly SiteContext $context) {}

	public function initialize():self
	{
		register_shutdown_function([$this, 'shutdown']);
		set_error_handler([$this, 'handleError']);
		set_exception_handler([$this, 'handleException']);
		return $this;
	}

	public function run():void
	{
		$site = new Site();
		$site->application = $this->context->getApplication();
		$site->application->configureResponder($this->context->responder);
		try {
			try {
				$this->context->interpreter
					->interpretRequest($site)
					->process($this->context)
					->deliver($this->context->responder);
			} catch (Exception $exception) {
				$exception
					->setEnvironment($this->context->environment)
					->deliver($this->context->responder);
				return;
			}
		}
		catch (\Exception $exception)
		{
			if($this->context->environment->inDevelopment)
			{
				echo $exception->getMessage();
			}
			else
			{
				ob_start();
				print_r($exception);
				error_log(ob_get_clean());
				echo 'SERVER ERROR';
			}
		}
    }

	public function shutdown():never
	{
		if(($error = error_get_last()) !== null &&
			$this->errorIsFatal($error['type']) &&
			!$this->fatalErrorHandled)
		{
			$this->handleError(...(array_values($error)));
			echo "SERVER ERROR";
			exit(1);
		}
		exit($error !== null && $this->errorIsFatal($error['type']) ? 1 : 0);
	}

	public function handleError(
		int $errno, string $errstr, ?string $errfile = null,
		?int $errline = null):bool
	{
		if($this->context->environment->inDevelopment)
		{
			printf(
				"<pre>PHP Error [%d]: %s\nFileMapper: %s\nLine: %s\n</pre>",
				$errno,
				htmlspecialchars($errstr, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($errfile ?? 'N/A', ENT_QUOTES, 'UTF-8'),
				$errline ?? 'N/A'
			);
		}
		else
		{
			error_log(sprintf(
				"Error - Type: %d, Message: %s, FileMapper: %s, Line: %d",
				$errno,
				$errstr,
				$errfile,
				$errline
			));
		}
		return !($this->fatalErrorHandled = $this->errorIsFatal($errno));
	}

	public function handleException(\Throwable $exception):never
	{
		if($this->context->environment->inDevelopment)
		{
			echo "<pre>";
			print_r($exception);
			echo "</pre>";
		}
		else
		{
			echo "UNCAUGHT SERVER ERROR";
		}
		exit(1);
	}

	private function errorIsFatal(int $errno):bool
	{
		return in_array($errno, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR,
			E_USER_ERROR, E_RECOVERABLE_ERROR, E_PARSE]);
	}
}