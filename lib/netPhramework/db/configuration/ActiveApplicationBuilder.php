<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Asset;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordProcess;
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
     */
    public function addAllAssetsWithDefaults():self
    {
        foreach($this->application->listAllRecordSets() as $name)
		{
			$this->newAsset($name)->includeDefaults();
		}
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

	public function childWithDefaults(string $name, string $linkField):self
	{
		$composer   = new self($this->application);
		$childAsset	= $composer->newAsset($name);
		$composer->includeDefaults();
		$childNode  = new ChildAsset($composer->get(), $linkField);
		$this->add($childNode);
		return $this;
	}

	public function setAsset(Asset $asset):self
	{
		$this->asset = $asset;
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