<?php

namespace Cartograph\Base;


interface IDenormalizable
{
	public function denormalize(array $data): void;
}