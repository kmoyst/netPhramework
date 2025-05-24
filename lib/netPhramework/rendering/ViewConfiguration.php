<?php

namespace netPhramework\rendering;


interface ViewConfiguration
{
	public function add(string $key, string|Encodable|
									 iterable|null $value):ViewConfiguration;

	public function setTitle(?string $title): ViewConfiguration;
}