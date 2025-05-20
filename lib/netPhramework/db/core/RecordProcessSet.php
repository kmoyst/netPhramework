<?php

namespace netPhramework\db\core;

use netPhramework\db\exceptions\ProcessNotFound;

final class RecordProcessSet
{
	private array $processes = [];

    /**
     * @param string $name
     * @return RecordProcess
     * @throws ProcessNotFound
     */
	public function getProcess(string $name):RecordProcess
	{
		if(!isset($this->processes[$name]))
			throw new ProcessNotFound("Not Found: $name");
		return $this->processes[$name];
	}

	public function addProcess(RecordProcess $process):RecordProcessSet
	{
		$this->processes[$process->getName()] = $process;
		return $this;
	}
}