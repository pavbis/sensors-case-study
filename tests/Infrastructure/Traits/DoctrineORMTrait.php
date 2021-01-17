<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use function sys_get_temp_dir;

trait DoctrineORMTrait
{
    /**
     * @return EntityManager
     * @throws ORMException
     */
    protected function createTestEntityManager(): EntityManagerInterface
    {
        if (!class_exists(EntityManager::class)) {
            TestCase::markTestSkipped('Doctrine ORM is not available.');
        }

        $connection = [
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'host' => getenv('DB_HOST'),
            'driver' => getenv('DB_DRIVER'),
        ];

        $config = $this->createTestConfiguration();

       return EntityManager::create($connection, $config);
    }

    /** just execute the same hell as Symfony does for yaml stuff */
    private function createTestConfiguration(): Configuration
    {
        $config = new Configuration;
        $config->setMetadataCacheImpl(new ArrayCache);
        $config->setQueryCacheImpl(new ArrayCache);
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('WhatEver\Doctrine');
        $config->setMetadataDriverImpl(new AnnotationDriver(new AnnotationReader, __DIR__ . '/../../../src/Entity'));

        return $config;
    }
}