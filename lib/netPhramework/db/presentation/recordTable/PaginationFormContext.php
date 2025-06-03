<?php

namespace netPhramework\db\presentation\recordTable;

class PaginationFormContext implements FormContext
{
	private FormContext $baseContext;
	private int $offset;

	public function setBaseContext(FormContext $baseContext): self
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

	public function getConditionSet(): array
	{
		return $this->baseContext->getConditionSet();
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