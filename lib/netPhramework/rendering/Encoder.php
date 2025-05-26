<?php

namespace netPhramework\rendering;

use netPhramework\common\FileFinder;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\UriFromLocation;
use netPhramework\dispatching\UriFromPath;
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
		return $this->encodeTemplate(
			$viewable->getTemplateName(), $viewable->getVariables());
	}

	public function encodeTemplate(
		string $templateName, ?iterable $variables = []):Buffer|string
	{
		try {
			$path = $this->findTemplatePath($templateName);
			$variables = $this->encodeIterable($variables);
			return new Buffer($path, $variables);
		} catch (FileNotFound) {
			// @TODO probably log the error here
			return "template missing: $templateName";
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
	 * @param Path $path
	 * @return string
	 */
	public function encodePath(Path $path):string
	{
		return new UriFromPath($path);
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

    private function encodeIterable(iterable $iterable):iterable
    {
        $a = [];
        foreach ($iterable as $k => $v) $a[$k] = $this->encode($v);
        return $a;
    }

    private function encode(
		Encodable|string|iterable|null $encodable):string|iterable
    {
		if($encodable instanceof Encodable)
			return $encodable->encode($this);
		elseif(is_string($encodable))
            return $this->encodeText($encodable);
        elseif(is_iterable($encodable))
            return $this->encodeIterable($encodable);
        else
            return '';
    }
}