<?php
namespace Cartograph\Base;


interface IMap
{
	public function from($source): IMap;
	public function fromArray(array $source): IMap;
	public function fromEach(array $source): IMap;
	public function keepIndexes(): IMap;
	
	/**
	 * @param string $target
	 * @return mixed
	 */
	public function into(string $target);
}