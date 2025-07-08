<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\exceptions\ConfigurationException;
use netPhramework\db\nodes\Delete;
use netPhramework\db\nodes\Insert;
use netPhramework\db\nodes\Update;
use netPhramework\locating\redirectors\Redirector;

class ActiveApplicationBuilder extends ApplicationBuilder
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
            $this->includeDefaults()->commit($name);
        return $this;
    }

	public function includeDefaults(): self
	{
		return $this
			->includeInsert()
			->includeUpdate()
			->includeDelete()
			;
	}

	public function childWithDefaults(
		string $mappedName,
		string $linkField,
		?string $assetName = null):self
	{
		$composer   = new self($this->mapper);
		$childAsset = $composer->includeDefaults()->get($mappedName,$assetName);
		$childNode  = new ChildAsset($childAsset, $linkField);
		$this->add($childNode);
		return $this;
	}

	public function includeInsert(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccess = null): self
	{
		$this->add(new Insert($saveProcess, $onSuccess));
		return $this;
	}

	public function includeUpdate(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccess = null): self
	{
		$this->add(new Update($saveProcess, $onSuccess));
		return $this;
	}

	public function includeInsertAndUpdate(
		?RecordProcess $saveProcess = null,
		?Redirector $onSuccess = null): self
	{
		$this->includeInsert($saveProcess, $onSuccess);
		$this->includeUpdate($saveProcess, $onSuccess);
		return $this;
	}

	public function includeDelete(
		?Redirector $onSuccess = null): self
	{
		$this->add(new Delete($onSuccess));
		return $this;
	}
}