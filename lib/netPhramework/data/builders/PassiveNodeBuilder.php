<?php

namespace netPhramework\data\builders;

use netPhramework\data\nodes\RecordBranch;
use netPhramework\data\presentation\recordForm\ChildRecordFormStrategy;
use netPhramework\data\presentation\recordForm\RecordFormStrategy;
use netPhramework\data\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\data\presentation\recordTable\ViewStrategy;
use netPhramework\data\resources\Add;
use netPhramework\data\resources\Browse;
use netPhramework\data\resources\Edit;
use netPhramework\nodes\Directory;

class PassiveNodeBuilder extends DataNodeBuilder
{
	/**
	 * This is a potent method, only meant to be used during initial
	 * development. It fetches every table name from the database and
	 * generates an asset with all default processes for each one and adds
	 * them to the Directory.
	 *
	 * @param Directory $directory
	 * @return self
	 */
    public function addAllAssetsWithDefaults(Directory $directory):self
    {
        foreach($this->mapper->listAllRecordSets() as $name)
        {
            $this->new($name)->includeDefaults()->commit($directory);
        }
        return $this;
    }

	public function includeDefaults(): self
	{
		return $this
			->includeAdd()
			->includeEdit()
			->includeBrowse();
	}

	public function branchWithDefaults(
		string $name,
		string $linkField):self
	{
		$asset = new self($this->mapper)
			->new($name)
			->includeAdd(new ChildRecordFormStrategy($linkField))
			->includeEdit(new ChildRecordFormStrategy($linkField))
			->get();
		$this->asset->recordNodeSet->add(new RecordBranch($asset, $linkField));
		return $this;
	}

	public function includeBrowse(
		?ColumnSetStrategy $columnSetStrategy = null,
		?ViewStrategy $tableViewStrategy = null,
		bool $isIndex = true): self
	{
		$process = new Browse($columnSetStrategy, $tableViewStrategy);
		if($isIndex)
			$this->add($process->asIndex());
		else
			$this->add($process);
		return $this;
	}

	public function includeEdit(
		?RecordFormStrategy $formStrategy = null): self
	{
		$this->add(new Edit($formStrategy));
		return $this;
	}

	public function includeAdd(
		?RecordFormStrategy $formStrategy = null): self
	{
		$this->add(new Add($formStrategy));
		return $this;
	}

	public function includeAddAndEdit(
		?RecordFormStrategy $formStrategy = null): self
	{
		return $this
			->includeAdd($formStrategy)
			->includeEdit($formStrategy)
			;
	}
}