<?php
namespace Cartograph;


use Cartograph\Base\IMap;
use PHPUnit\Framework\TestCase;


class CartographTest extends TestCase
{
	public function test_clone__willReturnInstanceOfCartograph()
	{
		$cartograph	= new Cartograph();
		$cloned		= clone $cartograph;
		$this->assertEquals($cartograph, $cloned);
	}
	
	public function test_addClass_callMethod_willReturnInstanceOfCartograph()
	{
		$cartograph = new Cartograph();
		$this->assertEquals($cartograph, $cartograph->addClass(new \stdClass()));
	}
	
	public function test_addDir_callMethod_willReturnInstanceOfCartograph()
	{
		$cartograph = new Cartograph();
		$this->assertEquals($cartograph, $cartograph->addDir(__DIR__ . '/Scanners/DirForScan/Classes'));
	}
	
	public function test_map_withSingleItem_willReturnMapCollection()
	{
		$cartograph = new Cartograph();
		$cartograph->addDir(__DIR__ . '/Scanners/DirForScan/Classes');
		$this->assertInstanceOf(IMap::class, $cartograph->map(1));
	}
	
	public function test_map_withArrayItems_willReturnMapCollection()
	{
		$cartograph = new Cartograph();
		$cartograph->addDir(__DIR__ . '/Scanners/DirForScan/Classes');
		$this->assertInstanceOf(IMap::class, $cartograph->map([1]));
	}
	
	public function test_map_withNoItems_willReturnMapCollection()
	{
		$cartograph = new Cartograph();
		$cartograph->addDir(__DIR__ . '/Scanners/DirForScan/Classes');
		$this->assertInstanceOf(IMap::class, $cartograph->map([1]));
	}
}
