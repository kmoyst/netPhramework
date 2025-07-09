<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\db\core\OneToManyLink;
use netPhramework\db\core\RecordProcess;
use netPhramework\db\nodes\Delete;
use netPhramework\db\nodes\Insert;
use netPhramework\db\nodes\Update;
use netPhramework\locating\redirectors\Redirector;

class ActiveRecordResourceBuilder extends RecordResourceBuilder
{
	/**
	 * This is a potent method, only meant to be used during initial
	 * development. It fetches every table name from the database and
	 * generates an asset with all default processes for each one and adds
	 * them to the Directory.
	 *
	 * @param Directory $node
	 * @return self
	 */
    public function addAllAssetsWithDefaults(Directory $node):self
    {
        foreach($this->mapper->listAllRecordSets() as $name)
		{
			$this->newAsset($name)->includeDefaults()->commit($node);
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

	public function oneToManyWithDefaults(string $name, string $linkField):self
	{
		$asset = new self($this->mapper)
			->newAsset($name)
			->includeDefaults()
			->get()
		;
		$this->add(new OneToManyLink($asset, $linkField));
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