<?php
namespace Cartograph\Scanners;


use Annotation\Flag;
use Annotation\Value;
use Cartograph\Maps\MapCollection;


class ClassAnnotationScanner
{
	private const MAPPER_MEHTOD	= 'map';
	private const SOURCE		= 'mapperSource';
	private const TARGET		= 'mapperTarget';
	
	
	public static function scan(string $className): MapCollection
	{
		$mapCollection = new MapCollection();
		
		$class = new \ReflectionClass($className);
		
		$methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
		
		foreach ($methods as $method)
		{
			if ($method->isStatic() && Flag::hasFlag($method, self::MAPPER_MEHTOD))
			{
				$method = $method->class . '::' . $method->name;
				
				$source = Value::getValue($method, self::SOURCE);
				$target = Value::getValue($method, self::TARGET);
				
				$mapCollection->add($source, $target, $method);
			}
		}
		
		return $mapCollection;
	}
}