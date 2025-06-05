<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\ConfigurationException;
use netPhramework\db\nodes\Delete;
use netPhramework\db\nodes\Insert;
use netPhramework\db\nodes\Update;
use netPhramework\locating\redirectors\Redirector;

class ActiveAssetBuilder extends AssetBuilder
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
            $this->defaults()->commit($name);
        return $this;
    }

	public function defaults(): self
	{
		return $this
			->insert()
			->update()
			->delete()
			;
	}

	public function childWithDefaults(
		string $mappedName,
		string $linkField,
		?string $assetName = null):self
	{
		$composer   = new self($this->mapper);
		$childAsset = $composer->defaults()->get($mappedName, $assetName);
		$childNode  = new ChildAsset($childAsset, $linkField);
		$this->node($childNode);
		return $this;
	}

	public function insert(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccess = null,
		?string        $processName = 'insert'): self
	{
		$this->node(new Insert($saveProcess, $onSuccess, $processName));
		return $this;
	}

	public function update(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccess = null,
		?string        $processName = 'update'): self
	{
		$this->node(new Update($saveProcess, $onSuccess, $processName));
		return $this;
	}

	public function insertAndUpdate(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccess = null): self
	{
		$this->insert($saveProcess, $onSuccess);
		$this->update($saveProcess, $onSuccess);
		return $this;
	}

	public function delete(
		?Redirector $onSuccess = null,
		string      $processName = 'delete'): self
	{
		$this->node(new Delete($onSuccess, $processName));
		return $this;
	}
}