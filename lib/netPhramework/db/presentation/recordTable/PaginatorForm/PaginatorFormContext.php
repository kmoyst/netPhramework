<?php

namespace netPhramework\db\presentation\recordTable\PaginatorForm;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormContext;

class PaginatorFormContext implements FilterFormContext
{
	private FilterFormContext $baseContext;

	public function getSortArray(): ?array
	{
		// TODO: Implement getSortArray() method.
	}

	public function getLimit(): ?int
	{
		// TODO: Implement getLimit() method.
	}

	public function getOffset(): int
	{
		// TODO: Implement getOffset() method.
	}

	public function getCount(): int
	{
		// TODO: Implement getCount() method.
	}


}