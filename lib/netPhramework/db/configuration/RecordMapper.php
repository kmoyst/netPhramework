<?php

namespace netPhramework\db\configuration;

interface RecordMapper extends RecordAccess, RecordSetFactory
{
	public function listAllRecordSets():array;
}