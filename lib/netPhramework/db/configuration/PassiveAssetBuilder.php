<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\ChildAsset;
use netPhramework\db\exceptions\ConfigurationException;
use netPhramework\db\presentation\recordForm\ChildRecordFormStrategy;
use netPhramework\db\presentation\recordForm\RecordFormStrategy;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\nodes\Add;
use netPhramework\db\nodes\Browse;
use netPhramework\db\nodes\Edit;

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
            $this->new($name)->defaults()->commit();
        }
        return $this;
    }

	public function defaults(): self
	{
		return $this
			->add()
			->edit()
			->browse();
	}

	public function childWithDefaults(string $name, string $linkField):self
	{
		$composer   = new self($this->mapper);
		$childAsset = $composer
			->new($name)
			->add(new ChildRecordFormStrategy($linkField))
			->edit(new ChildRecordFormStrategy($linkField))
			->get()
		;
		$childNode  = new ChildAsset($childAsset, $linkField);
		$this->node($childNode);
		return $this;
	}

	public function browse(
		?ColumnStrategy $columnSetStrategy = null,
		string          $processName = '',
		?ColumnMapper   $columnMapper = null): self
	{
		$this->node(new Browse(
			$columnSetStrategy, $processName, $columnMapper));
		return $this;
	}

	public function edit(
		?RecordFormStrategy $formStrategy = null,
		string $processName = 'edit'): self
	{
		$this->node(new Edit($formStrategy, $processName));
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
		$this->node(new Add($formStrategy, $processName));
		return $this;
	}
}