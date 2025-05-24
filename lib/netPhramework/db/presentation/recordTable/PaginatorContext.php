<?php

namespace netPhramework\db\presentation\recordTable;
class PaginatorContext implements FilterFormContext
{
	private int $offset;

	public function __construct(
		private readonly FilterFormContext $baseContext) {}

	public function setOffset(int $offset): PaginatorContext
	{
		$this->offset = $offset;
		return $this;
	}

	public function getSortArray(): ?array
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