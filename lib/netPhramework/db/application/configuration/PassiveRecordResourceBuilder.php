<?php

namespace netPhramework\db\application\configuration;

use netPhramework\core\BuildableNode;
use netPhramework\db\core\OneToManyLink;
use netPhramework\db\nodes\Add;
use netPhramework\db\nodes\Browse;
use netPhramework\db\nodes\Edit;
use netPhramework\db\presentation\recordForm\ChildRecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\db\presentation\recordTable\ViewStrategy;

class PassiveRecordResourceBuilder extends RecordResourceBuilder
{
	/**
	 * This is a potent method, only meant to be used during initial
	 * development. It fetches every table name from the database and
	 * generates an asset with all default processes for each one and adds
	 * them to the Directory.
	 *
	 * @param BuildableNode $node
	 * @return self
	 */
    public function addAllAssetsWithDefaults(BuildableNode $node):self
    {
        foreach($this->mapper->listAllRecordSets() as $name)
        {
            $this->newAsset($name)->includeDefaults()->commit($node);
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

	public function oneToManyWithDefaults(
		string $name,
		string $linkField):self
	{
		$asset = new self($this->mapper)
			->newAsset($name)
			->includeAdd(new ChildRecordFormStrategy($linkField))
			->includeEdit(new ChildRecordFormStrategy($linkField))
			->get();
		$this->add(new OneToManyLink($asset, $linkField));
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