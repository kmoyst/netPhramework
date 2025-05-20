<?php

namespace netPhramework\db\presentation\recordTable;

readonly class PaginatorCalculator
{
	public function __construct(
		private int $limit,
		private int $offset,
		private int $rowCount) {}

	public function previousOffset():int
	{
		$offset 	= $this->offset;
		$limit 		= $this->limit;
		$count 		= $this->rowCount;
		$previous 	= $offset - $limit;
		return
			$previous >= 0 ? $previous :
				($count % $limit ? $count - $count % $limit :
					$count - $limit);
	}

	public function nextOffset():int
	{
		$next = $this->offset + $this->limit;
		return $next < $this->rowCount ? $next : 0;
	}

	public function firstRowNumber():int
	{
		return $this->offset + 1;
	}

	public function lastRowNumber():int
	{
		return min($this->offset + $this->limit, $this->rowCount);
	}

	public function pageCount():int
	{
		return ceil($this->rowCount / $this->limit);
	}

	public function pageNumber():int
	{
		return ceil($this->offset / $this->limit) + 1;
	}
}