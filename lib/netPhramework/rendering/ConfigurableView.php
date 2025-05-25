<?php

namespace netPhramework\rendering;

interface ConfigurableView extends ConfigurableViewable
{
	public function setTitle(?string $title): ConfigurableView;
}