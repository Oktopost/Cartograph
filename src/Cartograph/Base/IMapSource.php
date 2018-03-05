<?php
namespace Cartograph\Base;


use Cartograph\Maps\MapCollection;


interface IMapSource
{
	public function getCollection(): MapCollection;
}