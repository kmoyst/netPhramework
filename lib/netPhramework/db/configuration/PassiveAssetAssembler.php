<?php

namespace netPhramework\db\configuration;

use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\processes\Add;
use netPhramework\db\processes\Browse;
use netPhramework\db\processes\Edit;

class PassiveAssetAssembler extends AssetAssembler
{
	public function defaults(): self
	{
		return $this
			->add()
			->edit()
			->browse();
	}

	public function browse(
		?ColumnStrategy $columnSetStrategy = null,
		string          $processName = '',
		?ColumnMapper   $columnMapper = null): self
	{
		$this->process(new Browse(
			$columnSetStrategy, $processName, $columnMapper));
		return $this;
	}

	public function edit(
		?RecordFormStrategy $formStrategy = null,
		string $processName = 'edit'): self
	{
		$this->process(new Edit($formStrategy, $processName));
		return $this;
	}

	/**
	 * Add an Add Record process
	 *
	 * @param RecordFormStrategy|null $formStrategy
	 * @param string $processName
	 * @return $this
	 */
	public function add(
		?RecordFormStrategy $formStrategy = null,
		string $processName = 'add'): self
	{
		$this->process(new Add($formStrategy, $processName));
		return $this;
	}
}