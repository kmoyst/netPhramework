<?php

namespace netPhramework\db\presentation\recordTable\PaginatorForm;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormContext;

class PaginatorFormContext implements FilterFormContext
{
	private FilterFormContext $baseContext;

	public function getSortArray():array
	{
		return $this->baseContext->getSortArray();
	}

	public function getLimit(): ?int
	{
		return $this->baseContext->getLimit();
	}

	public function getOffset(): int
	{
		// TODO: Implement getOffset() method.
	}

	public function getCount(): int
	{
		return $this->baseContext->getCount();
	}
}