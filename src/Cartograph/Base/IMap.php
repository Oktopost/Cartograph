<?php
namespace Cartograph\Base;


interface IMap
{
	public function from($source): IMap;
	public function fromArray(array $source): IMap;
	public function fromEach(array $source): IMap;
	public function getGroup(array $data, $key, ?callable $callback = null): array;
	public function keepIndexes(): IMap;
	
	/**
	 * @template R of object
	 * @param class-string<R> $target
	 * @return R[]
	 */
	public function into(string $target);
}