<?php
namespace tests;
require_once "../lib/netPhramework/bootstrap/Loader.php";
use netPhramework\cli\CliContext;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Router;
use netPhramework\nodes\Directory;
use netPhramework\resources\Page;

class Application implements \netPhramework\core\Application
{
	public function configurePassiveNode(Directory $root): void
	{
		$root->add(new Page('index'));
		$root->add(new Directory('users')
			->add(new Page('list'))->add(new Directory('1')->add(new Page('edit'))));
	}

	public function configureActiveNode(Directory $root): void
	{

	}
}
$router = new Router(new CliContext());
try {
	$router
		->openRequest(new Application())
		->andFindHandler();
	echo "Node '".$router->handler->getName()."' was found.".PHP_EOL;
} catch (Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
