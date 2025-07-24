<?php

namespace netPhramework\bootstrap;

use netPhramework\runtime\RuntimeMode;

class Handler
{
	private bool $fatalErrorHandled = false;

	public function __construct(private readonly RuntimeMode $mode) {}

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
		if($this->mode->isDevelopment())
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
		if($this->mode->isDevelopment())
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

	public function logException(\Exception $exception):void
	{
		ob_start();
		echo $exception->getCode();
		echo ": ";
		echo $exception->getMessage();
		error_log('Error:'. ob_get_clean());
	}

	private function errorIsFatal(int $errno):bool
	{
		return in_array($errno, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR,
			E_USER_ERROR, E_RECOVERABLE_ERROR, E_PARSE]);
	}
}