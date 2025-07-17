<?php

namespace tests;

use netPhramework\exceptions\PathException;
use netPhramework\nodes\Directory;
use netPhramework\nodes\Node;
use netPhramework\resources\Page;
use netPhramework\routing\Path;
use netPhramework\routing\PathFromArray;
use netPhramework\routing\PathFromCli;
use netPhramework\routing\PathFromUri;
use netPhramework\routing\Route;

class TestNodeOne implements TestNode
{
	private Node $root;

	public function getRoot():Node
	{
		if(!isset($this->nodeOne))
		{
			$index	   = new Page('index')->asIndex();
			$browse	   = new Page('browse')->asIndex();
			$editPage  = new Page('edit');
			$editPageI = new Page('edit')->asIndex();
			$addPage   = new Page('add');
			$taxClaims = new Directory('tax-claims');
			$ninety    = new Directory('90');
			$fifty	   = new Directory('50');
			$files     = new Directory('tax-claim-files');
			$root	   = new Directory('');
			$root->add($taxClaims)->add($index);
			$taxClaims->add($ninety)->add($browse)->add($addPage);
			$ninety->add($files)->add($editPage)->add($editPageI);
			$files->add($fifty)->add($browse)->add($addPage);
			$fifty->add($editPage)->add($editPageI);
			$this->root = $root;
		}
		return $this->root;
	}

	/**
	 * @return Path
	 * @throws PathException
	 */
	public function basePath():Path
	{
		return new Path()->setName('tax-claims')
			->appendName('90')
			->appendName('tax-claim-files')
			->appendName('50')
			->appendName('edit')
			;
	}

	public function fromCli():Path
	{
		return new PathFromCli();
	}

	public function fromArray():Path
	{
		return new PathFromArray(['tax-claims', '90',
			'tax-claim-files', '50', 'edit']);
	}

	public function fromUri():Path
	{
		return new PathFromUri('/tax-claims/90/tax-claim-files/50/edit');
	}

	public function fromArrayTail():Path
	{
		return new PathFromArray(['tax-claim-files','50', 'edit']);
	}

	public function fromArrayHead():Path
	{
		return new PathFromArray(['tax-claims', '90']);
	}

	public function fromUriHead():Path
	{
		return new PathFromUri('/tax-claims/90');
	}

	public function fromUriTail():Path
	{
		return new PathFromUri('/tax-claim-files/50/edit');
	}
}