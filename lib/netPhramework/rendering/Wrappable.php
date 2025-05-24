<?php

namespace netPhramework\rendering;

interface Wrappable
{
	/**
	 * Title of content being wrapped to be incorporated into top level title
	 *
	 * @return string
	 */
    public function getTitle():string;

	/**
	 * An encodable content
	 *
	 * @return Encodable|string
	 */
    public function getContent():Encodable|string;
}