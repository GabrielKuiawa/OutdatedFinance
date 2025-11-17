<?php

namespace Tests\Unit\Models\Resource;

use App\Models\Resource;
use App\Services\FileService;
use Core\Database\ActiveRecord\BelongsTo;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testHasCorrectTableAndColumns(): void
    {
        $reflection = new \ReflectionClass(Resource::class);

        $table = $reflection->getProperty('table');
        $table->setAccessible(true);

        $columns = $reflection->getProperty('columns');
        $columns->setAccessible(true);

        $this->assertEquals('resources', $table->getValue());
        $this->assertEquals(['file_path', 'expenses_id'], $columns->getValue());
    }

    public function testBelongsToExpense(): void
    {
        $resource = new Resource();
        $relation = $resource->expense();

        $this->assertInstanceOf(BelongsTo::class, $relation);
    }

    public function testCreatesFileServiceInstance(): void
    {
        $resource = new Resource();
        $fileService = $resource->resourceFiles();

        $this->assertInstanceOf(FileService::class, $fileService);
    }

    public function testResourceFilesReceivesResourceInstance(): void
    {
        $resource = new Resource();
        $fileService = $resource->resourceFiles();

        $reflection = new \ReflectionClass($fileService);
        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);

        $this->assertSame($resource, $modelProperty->getValue($fileService));
    }



    public function testStaticPropertiesExist(): void
    {
        $reflection = new \ReflectionClass(Resource::class);

        $this->assertTrue($reflection->hasProperty('table'));
        $this->assertTrue($reflection->hasProperty('columns'));

        $this->assertTrue($reflection->getProperty('table')->isProtected());
        $this->assertTrue($reflection->getProperty('columns')->isProtected());
    }
}
