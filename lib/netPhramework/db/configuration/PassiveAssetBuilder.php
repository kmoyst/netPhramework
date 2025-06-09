<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\ChildAsset;
use netPhramework\db\exceptions\ConfigurationException;
use netPhramework\db\nodes\Add;
use netPhramework\db\nodes\Browse;
use netPhramework\db\nodes\Edit;
use netPhramework\db\presentation\recordForm\ChildRecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordTable\columnSet\ColumnSetStrategy;
use netPhramework\db\presentation\recordTable\ViewStrategy;

class PassiveAssetBuilder extends AssetBuilder
{
    /**
     * This is a potent method, only meant to be used during initial
     * development. It fetches every table name from the database and
     * generates an asset with all default processes for each one and adds
     * them to the Directory.
     *
     * @return self
	 * @throws ConfigurationException
     */
    public function addAllAssetsWithDefaults():self
    {
        foreach($this->mapper->listAllRecordSets() as $name)
        {
            $this->includeDefaults()->commit($name);
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

	public function childWithDefaults(
		string $mappedName,
		string $linkField,
		?string $assetName = null):self
	{
		$builder 	= new self($this->mapper);
		$childAsset = $builder
			->includeAdd(new ChildRecordFormStrategy($linkField))
			->includeEdit(new ChildRecordFormStrategy($linkField))
			->get($mappedName, $assetName)
		;
		$childNode = new ChildAsset($childAsset, $linkField);
		$this->add($childNode);
		return $this;
	}

	public function includeBrowse(
		?ColumnSetStrategy $columnSetStrategy = null,
		?ViewStrategy $tableViewStrategy = null,
		string $processName = ''): self
	{
		$this->add(new Browse(
			$columnSetStrategy, $tableViewStrategy, $processName));
		return $this;
	}

	public function includeEdit(
		?RecordFormStrategy $formStrategy = null,
		string $processName = 'edit'): self
	{
		$this->add(new Edit($formStrategy, $processName));
		return $this;
	}

	public function includeAdd(
		?RecordFormStrategy $formStrategy = null,
		string $processName = 'add'): self
	{
		$this->add(new Add($formStrategy, $processName));
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