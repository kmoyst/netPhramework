<?php

namespace netPhramework\db\core;

use netPhramework\db\exceptions\ProcessNotFound;

final class RecordSetProcessSet
{
    private array $processes = [];

    public function addProcess(RecordSetProcess $process):RecordSetProcessSet
    {
        $this->processes[$process->getName()] = $process;
        return $this;
    }

    /**
     * @param string $name
     * @return RecordSetProcess
     * @throws ProcessNotFound
     */
    public function getProcess(string $name):RecordSetProcess
    {
        if(!isset($this->processes[$name]))
            throw new ProcessNotFound("Not Found: $name");
        return $this->processes[$name];
    }
}