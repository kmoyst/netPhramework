<?php

namespace netPhramework\db\presentation\recordTable\collation;

use netPhramework\db\mapping\Glue;

class CollatorResult
{
	private array $idSet = [];
	private array $glues = [];

	public function addIds(array $ids):self
	{
		$this->idSet[] = $ids;
		return $this;
	}

	public function addGlue(?Glue $glue):self
	{
		$this->glues[] = $glue;
		return $this;
	}

	public function getIdSet(): array
	{
		return $this->idSet;
	}

	public function getGlues(): array
	{
		return $this->glues;
	}

	public function isEmpty():bool
	{
		return empty($this->idSet);
	}
}