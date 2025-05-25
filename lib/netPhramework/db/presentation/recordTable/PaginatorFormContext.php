<?php

namespace netPhramework\db\presentation\recordTable;

class PaginatorFormContext implements FilterFormContext
{
	private FilterFormContext $baseContext;
	private int $offset;

	public function setBaseContext(FilterFormContext $baseContext): self
	{
		$this->baseContext = $baseContext;
		return $this;
	}

	public function setOffset(int $offset): self
	{
		$this->offset = $offset;
		return $this;
	}

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
		return $this->offset;
	}

	public function getCount(): int
	{
		return $this->baseContext->getCount();
	}
}