<?php

namespace Cartograph\Base;


interface INormalizable
{
	public function normalize(bool $keepFalseLike = false): array;
}