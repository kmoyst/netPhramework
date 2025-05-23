<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

class MutableLocation implements Relocatable, Location
{
    private Path $path;
    private Variables $parameters;

    /**
     * @param Path|null $path
     * @param Variables|null $parameters
     */
    public function __construct(?Path $path = null,
                                ?Variables $parameters = null)
    {
        $this->path = $path ?? new Path();
        $this->parameters = $parameters ?? new Variables();
    }


    public function getPath(): Path
	{
		return $this->path;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}