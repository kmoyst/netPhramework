<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\RecordProcess;
use netPhramework\db\processes\Delete;
use netPhramework\db\processes\Insert;
use netPhramework\db\processes\Update;
use netPhramework\db\strategies\RecordSaveStrategy;
use netPhramework\dispatching\Dispatcher;

class WriteablesAssembler extends AssetAssembler
{
	public function defaults(): self
	{
		return $this
			->insert()
			->update()
			->delete()
			;
	}

	public function insert(
		?RecordProcess $saveProcess = null,
		?Dispatcher $onSuccess = null,
		?string $processName = 'insert'): self
	{
		$this->process(new Insert($saveProcess, $onSuccess, $processName));
		return $this;
	}

	public function update(
		?RecordProcess $saveProcess = null,
		?Dispatcher $onSuccess = null,
		?string $processName = 'update'): self
	{
		$this->process(new Update($saveProcess, $onSuccess, $processName));
		return $this;
	}

	public function insertAndUpdate(
		?RecordProcess $saveProcess = null,
		?Dispatcher $onSuccess = null): self
	{
		$this->insert($saveProcess, $onSuccess);
		$this->update($saveProcess, $onSuccess);
		return $this;
	}

	public function delete(
		?Dispatcher $onSuccess = null,
		string $processName = 'delete'
	): self
	{
		$this->process(new Delete($onSuccess, $processName));
		return $this;
	}
}