<?php
namespace Cartograph\Utilities;


use Cartograph\Base\INormalizable;
use Objection\LiteSetup;
use Objection\LiteObject;

use PHPUnit\Framework\TestCase;


class NarrowTest extends TestCase
{
	public function test_toArray_ObjectWithoutProperties_ReturnEmptyArray(): void
	{
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray(new class extends LiteObject
			{
				protected function _setup() { return []; }
			}),
			[]
		);
	}
	
	public function test_toArray_ObjectWithProperties_ReturnProperties(): void
	{
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createString('abc'),
						'B'	=> LiteSetup::createInt('123')
					]; 
				}
			}),
			[
				'A' => 'abc',
				'B' => '123'
			]
		);
	}
	
	public function test_toArray_ProperyWithValueNull_PropertySkipped(): void
	{
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createString('abc'),
						'B'	=> LiteSetup::createInt(null)
					]; 
				}
			}),
			[
				'A' => 'abc'
			]
		);
	}
	
	public function test_toArray_FalseLikeProperties_ValuesReturned(): void
	{
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createString(''),
						'B'	=> LiteSetup::createInt(0),
						'C'	=> LiteSetup::createBool(false)
					]; 
				}
			}),
			[
				'A' => '',
				'B'	=> 0,
				'C' => false
			]
		);
	}
	
	public function test_toArray_PropertyIsAnotherObject_ValuesReturned(): void
	{
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class)
				]; 
			}
		};
		
		$parent->A = new ChildClass(['child_A' => '123', 'child_B' => 456]);
		
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray($parent),
			[
				'A' =>
				[
					'child_A' => '123',
					'child_B' => 456
				]
			]
		);
	}
	
	public function test_toArray_PropertyInChildObjectIsNull_NullValuesReturned(): void
	{
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class)
				]; 
			}
		};
		
		$parent->A = new ChildClass(['child_A' => '123']);
		
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray($parent),
			[
				'A' =>
				[
					'child_A' => '123'
				]
			]
		);
	}
	
	public function test_toArray_AllPropertiesInChildObjectAreNull_ObjectIsEmptyArray(): void
	{
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class),
					'B'	=> LiteSetup::createInt(123)
				]; 
			}
		};
		
		$parent->A = new ChildClass();
		
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray($parent),
			[
				'A'	=> [],
				'B' => 123
			]
		);
	}
	
	public function test_toArray_ChildObjectIsNull_PropertyIgnored(): void
	{
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class),
					'B'	=> LiteSetup::createInt(123)
				]; 
			}
		};
		
		/** @noinspection PhpParamsInspection */
		self::assertEquals(
			Narrow::toArray($parent),
			[ 'B' => 123 ]
		);
	}
	
	
	public function test_toArray_NormalizableObjectPassed_normalizeMethodCalled(): void
	{
		$obj = new ChildNormalizerTestClass();
		$obj->a = 123;
		$obj->b = 'abc';
		
		self::assertEquals(
			Narrow::toArray($obj),
			[
				'a' => 123,
				'b' => 'abc'
			]
		);
	}
	
	public function test_toArray_NormalizableObjectWithNullValue_ValueExcluded(): void
	{
		$obj = new ChildNormalizerTestClass();
		$obj->a = 123;
		
		self::assertEquals(
			Narrow::toArray($obj),
			[
				'a' => 123
			]
		);
	}
	
	public function test_toArray_AllValuesAreNulls_ReturnEmptyArray(): void
	{
		$obj = new ChildNormalizerTestClass();
		self::assertEmpty(Narrow::toArray($obj), []);
	}
	
	public function test_toArray_AValueIsAnotherSerializableObject_ReturnCorrectArray(): void
	{
		$obj = new ChildNormalizerTestClass();
		$obj->a = ['a' => 123, 'b' => null];
		
		self::assertEquals(
			Narrow::toArray($obj), 
			['a' => ['a' => 123]]
		);
	}
	
	public function test_toArray_ValueInsideArrayIsNormalizable_ReturnCorrectArray(): void
	{
		$obj = new ChildNormalizerTestClass();
		$obj->a = ['a' => 123, 'b' => null];
		
		self::assertEquals(
			Narrow::toArray(['inn' => $obj]), 
			['inn' => ['a' => ['a' => 123]]]
		);
	}
	
	
	/**
	 * @expectedException \Cartograph\Exceptions\CartographFatalException
	 */
	public function test_toArray_InvalidTypePassed_ExceptionThrown(): void
	{
		/** @noinspection PhpParamsInspection */
		Narrow::toArray($this);
	}
	
	
	/**
	 * @expectedException \Cartograph\Exceptions\CartographFatalException
	 */
	public function test_toArray_ValueInAnArrayIsNotScalar_ExceptionThrown(): void
	{
		Narrow::toArray(['a' => $this]);
	}
}


class ChildClass extends LiteObject 
{
	protected function _setup() 
	{
		return [
			'child_A'	=> LiteSetup::createString(null),
			'child_B'	=> LiteSetup::createInt(null)
		]; 
	}
	
	
	public function __construct(array $values = [])
	{
		parent::__construct();
		$this->fromArray($values);
	}
}


class ChildNormalizerTestClass implements INormalizable
{
	public $a;
	public $b;
	
	
	public function normalize(): array
	{
		return [
			'a' => $this->a,
			'b' => $this->b
		];
	}
}