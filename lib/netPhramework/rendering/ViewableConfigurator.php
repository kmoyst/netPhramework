<?php

namespace netPhramework\rendering;

interface ViewableConfigurator
{
	public function configureViewable(ConfigurableViewable $viewable):void;
}