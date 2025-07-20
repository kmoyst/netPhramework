<?php

namespace netPhramework\data\presentation\recordTable\collation;

readonly class CollationMap
{
	public function __construct(
		private array $unfilteredIds,
		private ?array $filteredIds,
		private ?array $sortedIds,
		private ?array $paginatedIds) {}

	public function getUnfilteredIds(): array
	{
		return $this->unfilteredIds;
	}

	public function getFilteredIds(bool $fallback = true): ?array
	{
		return $this->filteredIds ??
			($fallback ? $this->getUnfilteredIds() : null);
	}

	public function getSortedIds(bool $fallback = true): ?array
	{
		return $this->sortedIds ??
			($fallback ? $this->getFilteredIds($fallback) : null);
	}

	public function getPaginatedIds(bool $fallback = true): ?array
	{
		return $this->paginatedIds ??
			($fallback ? $this->getSortedIds($fallback) : null);
	}
}