<?php

namespace netPhramework\db\application;

use netPhramework\db\resources\Delete;
use netPhramework\db\resources\Insert;
use netPhramework\db\resources\Update;
use netPhramework\db\nodes\Branch;
use netPhramework\db\nodes\RecordProcess;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\nodes\Directory;

class ActiveNodeBuilder extends DynamicNodeBuilder
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
		$this->asset->recordChildSet->add(new Branch($asset, $linkField));
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