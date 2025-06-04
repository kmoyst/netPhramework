<?php

namespace netPhramework\db\presentation\recordTable\collation;

use netPhramework\db\mapping\Glue;

class CollatorResult
{
	private array $collations = [];
	private array $glues      = [];

	public function addCollation(array $collation):self
	{
		$this->collations[] = $collation;
		return $this;
	}

	public function addGlue(?Glue $glue):self
	{
		$this->glues[] = $glue;
		return $this;
	}

	public function getCollations(): array
	{
		return $this->collations;
	}

	public function getGlues(): array
	{
		return $this->glues;
	}

	public function isEmpty():bool
	{
		return empty($this->collations);
	}
}