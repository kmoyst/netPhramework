<?php

namespace netPhramework\db\presentation\recordTable\paginator;

class PaginatorCalculator
{
	private int $limit;
	private int $currentOffset;
	private int $totalCount;

	public function setLimit(int $limit): self
	{
		$this->limit = $limit;
		return $this;
	}

	public function setCurrentOffset(int $currentOffset): self
	{
		$this->currentOffset = $currentOffset;
		return $this;
	}

	public function setTotalCount(int $totalCount): self
	{
		$this->totalCount = $totalCount;
		return $this;
	}

	public function previousOffset():int
	{
		$offset 	= $this->currentOffset;
		$limit 		= $this->limit;
		$count 		= $this->totalCount;
		$previous 	= $offset - $limit;
		return
			$previous >= 0 ? $previous :
				($count % $limit ? $count - $count % $limit :
					$count - $limit);
	}

	public function nextOffset():int
	{
		$next = $this->currentOffset + $this->limit;
		return $next < $this->totalCount ? $next : 0;
	}

	public function firstRowNumber():int
	{
		return $this->currentOffset + 1;
	}

	public function lastRowNumber():int
	{
		return min($this->currentOffset + $this->limit, $this->totalCount);
	}

	public function pageCount():int
	{
		return ceil($this->totalCount / $this->limit);
	}

	public function pageNumber():int
	{
		return ceil($this->currentOffset / $this->limit) + 1;
	}
}