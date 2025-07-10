<?php

namespace netPhramework\db\application;

use netPhramework\db\exceptions\TreeBuilderException;
use netPhramework\db\resources\OneToManyLink;
use netPhramework\db\resources\RecordProcess;
use netPhramework\db\processes\Delete;
use netPhramework\db\processes\Insert;
use netPhramework\db\processes\Update;
use netPhramework\locating\redirectors\Redirector;

class ActiveTreeBuilder extends TreeBuilder
{
	/**
	 * This is a potent method, only meant to be used during initial
	 * development. It fetches every table name from the database and
	 * generates an asset with all default processes for each one and adds
	 * them to the Directory.
	 *
	 * @return self
	 * @throws TreeBuilderException
	 */
    public function addAllAssetsWithDefaults():self
    {
        foreach($this->mapper->listAllRecordSets() as $name)
		{
			$this->includeDefaults()->commit($name);
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
			->includeDefaults()
			->get($name)
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