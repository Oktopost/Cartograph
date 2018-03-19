<?php
namespace Cartograph\Scanners;


use Cartograph\Exceptions\Scanners\DirectoryScannerException;
use Itarator\IConsumer;
use Itarator\Filters\PHPFileFilter;

use Cartograph\Base\IMapper;
use Cartograph\Maps\MapCollection;


class RecursiveDirectoryScanner implements IConsumer
{
	private static $root; 
	private static $exceptions;
	
	
	private static function findMapperFunctions(array $classes): MapCollection
	{
		$mapCollection = new MapCollection();
		
		foreach ($classes as $class)
		{
			$ref = new \ReflectionClass($class);
			
			if ($ref->implementsInterface(IMapper::class))
			{
				$mapCollection->merge(ClassAnnotationScanner::scan($class));
			}
		}
		return $mapCollection;
	}
	
	public static function scan(string $dir): MapCollection
	{
		$iterator = new \Itarator();
		
		$iterator->setFileFilter(new PHPFileFilter());
		$iterator->setRootDirectory($dir);
		$iterator->setFileConsumer(new self());
		
		self::$root  = $iterator->getConfig()->RootDir;
		
		$classesBeforeScan = get_declared_classes();
		
		$iterator->execute();
		
		if (self::$exceptions)
			throw new DirectoryScannerException(self::$exceptions, self::$root);
		
		$classesAfterScan = array_diff(get_declared_classes(), $classesBeforeScan);
		
		return self::findMapperFunctions($classesAfterScan);
	}
	
	/**
	 * @param string $path
	 */
	public function consume($path)
	{
		try
		{
			$root = self::$root;
			
			/** @noinspection PhpIncludeInspection */
			require_once "$root/$path";
		}
		catch (\Throwable $t)
		{
			self::$exceptions[] = $t;
		}
	}
}