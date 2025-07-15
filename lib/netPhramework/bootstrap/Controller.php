<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Router;

readonly class Controller
{
	public function __construct(private Site $site) {}

	/**
	 * @return void
	 * @throws InvalidSession
	 */
	public function run():void
	{
		$this->initialize()->exchange();
	}

	/**
	 * @return self
	 * @throws InvalidSession
	 */
	private function initialize():self
	{
		$handler = new Handler($this->site->env->inDevelopment);
		register_shutdown_function([$handler, 'shutdown']);
		set_error_handler([$handler, 'handleError']);
		set_exception_handler([$handler, 'handleException']);
		$this->site->initialize();
		return $this;
	}

	private function exchange():void
	{
		try {
			try {
				new Router($this->site->application)
					->openRequest($this->site->request)
					->andFindHandler($this->site->request)
					->toProcessExchange($this->site->env, $this->site->services)
					->andDeliverResponseThrough($this->site->responder);
			} catch (NodeNotFound $exception) {
				$exception
					->setEnvironment($this->site->env)
					->deliver($this->site->responder);
			} catch (Exception $exception) {
				$exception
					->setEnvironment($this->site->env)
					->deliver($this->site->responder);
				$this->logException($exception);
			}
		}
		catch (\Exception $exception)
		{
			if($this->site->env->inDevelopment)
			{
				echo $exception->getMessage();
			}
			else
			{
				$this->logException($exception);
				echo 'SERVER ERROR';
			}
		}
    }

	private function logException(\Exception $exception):void
	{
		ob_start();
		echo $exception->getCode();
		echo ": ";
		echo $exception->getMessage();
		error_log('Error:'. ob_get_clean());
	}
}