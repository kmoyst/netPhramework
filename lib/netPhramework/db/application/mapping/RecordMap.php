<?php

namespace netPhramework\db\application\mapping;

readonly class RecordMap
{
	public function __construct
	(
	public string $assetName,
	public string $mappedName
	)
	{}
}