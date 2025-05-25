<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

/**
 * A fully readable and modifiable MutableLocation
 *
 */
class MutableLocation extends Location
{
    private MutablePath $path;
    private Variables $parameters;

    /**
     * @param MutablePath|null $path
     * @param Variables|null $parameters
     */
    public function __construct(?MutablePath $path = null,
                                ?Variables   $parameters = null)
    {
        $this->path = $path ?? new MutablePath();
        $this->parameters = $parameters ?? new Variables();
    }


    public function getPath(): MutablePath
	{
		return $this->path;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}