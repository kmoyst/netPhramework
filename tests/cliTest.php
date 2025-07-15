<?php

use netPhramework\cli\CliInput;
use netPhramework\cli\CliInterpreter;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Interpreter;
use netPhramework\exchange\Navigator;
use netPhramework\nodes\Directory;
use netPhramework\resources\Page;

require_once "../lib/netPhramework/bootstrap/Loader.php";
$interpreter = new Interpreter(new CliInput());
$request = $interpreter->interpret();
$root = new Directory('');
$root->add(new Page('index'));
$root->add(new Directory('users')
	->add(new Page('list'))->add(new Directory('1')->add(new Page('edit'))));
$navigator = new Navigator();
$navigator->setRoot($root)->setPath($request->location->path);
try {
	$resource = $navigator->navigate();
	echo $resource->getName() . " was found\n";
} catch (NodeNotFound $e) {
	echo "Node Not Found\n";
}