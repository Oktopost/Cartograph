<?php
namespace Cartograph\Scanners;


use Itarator\Filters\PHPFileFilter;

use Cartograph\Base\IMapper;
use Cartograph\Maps\MapCollection;
use Cartograph\Exceptions\Scanners\DirectoryScannerException;


class RecursiveDirectoryScanner
{
	private static $dir; 
	
	private static function findMapperFunctions(array $classes): MapCollection
	{
		$mapCollection = new MapCollection();
		
		foreach ($classes as $class)
		{
			$ref = new \ReflectionClass($class);
			
			$classPath = str_replace("\\", '/', $ref->getNamespaceName());
			
			if ($classPath && $ref->implementsInterface(IMapper::class))
			{
				$mapCollection->merge(ClassAnnotationScanner::scan($class));
			}
		}
		
		return $mapCollection;
	}
	
	
	public static function scan(string $dir): MapCollection
	{
		self::$dir = $dir;
		
		$iterator = new \Itarator();
		$consumer = new FileConsumer();
		
		$iterator->setFileFilter(new PHPFileFilter());
		$iterator->setRootDirectory($dir);
		$iterator->setFileConsumer($consumer);
		
		$consumer->setRoot($iterator->getConfig()->RootDir);
		
		$iterator->execute();
		
		if ($consumer->getExceptions())
			throw new DirectoryScannerException($consumer->getExceptions(), $iterator->getConfig()->RootDir);
		
		return self::findMapperFunctions(get_declared_classes());
	}
}