<?php

namespace netPhramework\db\presentation\recordTable\pagination;

use netPhramework\db\presentation\recordTable\query\QueryInterface;

/**
 * Wraps a Select Query to alter offset for pagination
 */
class Query implements QueryInterface
{
	private QueryInterface $baseQuery;
	private int $offset;

	public function setBaseQuery(QueryInterface $baseQuery): self
	{
		$this->baseQuery = $baseQuery;
		return $this;
	}

	public function setOffset(int $offset): self
	{
		$this->offset = $offset;
		return $this;
	}

	public function getSortArray():array
	{
		return $this->baseQuery->getSortArray();
	}

	public function getLimit(): ?int
	{
		return $this->baseQuery->getLimit();
	}

	public function getConditionSet(): array
	{
		return $this->baseQuery->getConditionSet();
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function getCount(): int
	{
		return $this->baseQuery->getCount();
	}
}