<?php

namespace netPhramework\rendering;

interface Wrappable
{
    public function getTitle():string;
    public function getContent():Viewable;
}