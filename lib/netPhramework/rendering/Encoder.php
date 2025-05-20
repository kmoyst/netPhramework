<?php

namespace netPhramework\rendering;

use netPhramework\common\FileFinder;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\UriFromLocation;
use netPhramework\exceptions\FileNotFound;

readonly class Encoder
{
	public function __construct(private FileFinder $templateFinder) {}

	public function encodeText(string $text):string
	{
		return htmlspecialchars($text);
	}

	public function encodeViewable(Viewable $viewable):Buffer|string
    {
		try {
			$path = $this->findTemplatePath($viewable->getTemplateName());
			$variables = $this->encodeIterable($viewable->getVariables());
			return new Buffer($path, $variables);
		} catch (FileNotFound) {
            // @TODO probably log the error here
			return 'template missing';
		}
	}

	/**
	 * @param Location $location
	 * @return string
	 */
	public function encodeLocation(Location $location):string
	{
		return new UriFromLocation($location);
	}

	/**
	 * @param string $templateName
	 * @return string
	 * @throws FileNotFound
	 */
    private function findTemplatePath(string $templateName):string
    {
		return $this->templateFinder->findPath($templateName);
	}

    private function encodeIterable(iterable $iterable):array
    {
        $a = [];
        foreach ($iterable as $k => $v) $a[$k] = $this->encode($v);
        return $a;
    }

    private function encode(
        Encodable|Viewable|Location|
		string|iterable|null $encodable):string|array
    {
		if($encodable instanceof Encodable)
			return $encodable->encode($this);
        elseif($encodable instanceof Viewable)
            return $this->encodeViewable($encodable);
		elseif($encodable instanceof Location)
			return $this->encodeLocation($encodable);
        elseif(is_string($encodable))
            return $this->encodeText($encodable);
        elseif(is_iterable($encodable))
            return $this->encodeIterable($encodable);
        else
            return '';
    }
}