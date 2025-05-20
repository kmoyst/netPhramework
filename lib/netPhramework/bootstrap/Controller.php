<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;

class Controller
{
	private ?Environment $environment = null;
	private bool $fatalErrorHandled   = false;

	public function run(SiteContext $context):void
	{
		register_shutdown_function([$this, 'shutdown']);
		set_error_handler([$this, 'handleError']);
		set_exception_handler([$this, 'handleException']);

		$this->environment = $context->getEnvironment();

		$application = new Application();

		$responder 	 = $context->getResponder($context->getEncoder());
		$interpreter = $context->getRequestInterpreter();
		$config	     = $context->getConfiguration();

		try
		{
			try
			{
				$application->configure($config);
			}
			catch (Exception $exception)
			{
				$exception
					->setEnvironment($this->environment)
					->deliver($responder);
				return;
			}
			$interpreter
				->establishRequest($application)
				->process($context)
				->deliver($responder)
			;
		}
		catch (\Exception $exception)
		{
			if($this->environment->inDevelopment())
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
			$this->handleError(...$error);
			echo "SERVER ERROR";
			exit(1);
		}
		exit($error!==null&&$this->errorIsFatal($error['type'])?1:0);
	}
	public function handleError(
		int $errno, string $errstr, ?string $errfile = null,
		?int $errline = null, ?array $errcontext = null):bool
	{
		if($this->environment?->inDevelopment())
		{
			printf(
				"<pre>PHP Error [%d]: %s\nFile: %s\nLine: %s\n</pre>",
				$errno,
				htmlspecialchars($errstr, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($errfile ?? 'N/A', ENT_QUOTES, 'UTF-8'),
				$errline ?? 'N/A'
			);
		}
		else
		{
			error_log(sprintf(
				"Error - Type: %d, Message: %s, File: %s, Line: %d",
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
		if($this->environment?->inDevelopment())
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