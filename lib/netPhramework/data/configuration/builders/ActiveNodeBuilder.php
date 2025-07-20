<?php

namespace netPhramework\data\configuration\builders;

use netPhramework\data\nodes\RecordBranch;
use netPhramework\data\nodes\RecordProcess;
use netPhramework\data\resources\Delete;
use netPhramework\data\resources\Insert;
use netPhramework\data\resources\Update;
use netPhramework\nodes\Directory;
use netPhramework\routing\redirectors\Redirector;

class ActiveNodeBuilder extends DataNodeBuilder
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

	public function includeDefaults(
		?RecordProcess $saveProcess = null,
		?Redirector    $onSuccessfulSave = null): self
	{
		return $this
			->includeInsert($saveProcess, $onSuccessfulSave)
			->includeUpdate($saveProcess, $onSuccessfulSave)
			->includeDelete()
			;
	}

	public function branchWithDefaults(string $name, string $linkField):self
	{
		$asset = new self($this->mapper)
			->new($name)
			->includeDefaults()
			->get()
		;
		$this->asset->recordNodeSet->add(new RecordBranch($asset, $linkField));
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
		?Redirector    $onSuccess = null): self
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