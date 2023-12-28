<?php

namespace App\Tests\Unit\Infrastructure\Presentation;

use App\Application\Presentation\OutputInterface\CollectionOutputInterface;
use App\Application\Presentation\OutputInterface\ObjectOutputInterface;
use App\Application\Presentation\OutputInterface\OutputInterface;
use App\Application\Presentation\OutputInterface\PaginatedCollectionOutputInterface;
use App\Application\Presentation\OutputInterface\ScalarOutputInterface;
use App\Application\Presentation\PaginatedResponseKeyEnum;
use App\Infrastructure\Presentation\PresenterException;
use App\Infrastructure\Presentation\RawPresenter;
use PHPUnit\Framework\TestCase;

class RawPresenterTest extends TestCase
{
    private RawPresenter $presenter;

    protected function setUp(): void
    {
        $this->presenter = new RawPresenter();
    }

    public function testPresentWithCollectionOutputInterface(): void
    {
        $outputMock = $this->createMock(CollectionOutputInterface::class);
        $expectedData = ['item1', 'item2'];
        $outputMock->method('getArray')->willReturn($expectedData);

        $this->presenter->present($outputMock);
        $this->assertEquals($expectedData, $this->presenter->getData());
    }

    public function testPresentWithObjectOutputInterface(): void
    {
        $outputMock = $this->createMock(ObjectOutputInterface::class);
        $expectedData = new \stdClass();
        $outputMock->method('getObject')->willReturn($expectedData);

        $this->presenter->present($outputMock);
        $this->assertSame($expectedData, $this->presenter->getData());
    }

    public function testPresentWithScalarOutputInterface(): void
    {
        $outputMock = $this->createMock(ScalarOutputInterface::class);
        $expectedData = 123;
        $outputMock->method('getData')->willReturn($expectedData);

        $this->presenter->present($outputMock);
        $this->assertSame($expectedData, $this->presenter->getData());
    }

    public function testPresentWithPaginatedCollectionOutputInterface(): void
    {
        $outputMock = $this->createMock(PaginatedCollectionOutputInterface::class);
        $expectedData = [
            PaginatedResponseKeyEnum::Data->value => ['item1', 'item2'],
            PaginatedResponseKeyEnum::Page->value => 0,
            PaginatedResponseKeyEnum::Size->value => 2,
            PaginatedResponseKeyEnum::Total->value => 2,
        ];
        $outputMock->method('getData')->willReturn(['item1', 'item2']);
        $outputMock->method('getPage')->willReturn(0);
        $outputMock->method('getSize')->willReturn(2);
        $outputMock->method('getTotal')->willReturn(2);

        $this->presenter->present($outputMock);
        $this->assertSame($expectedData, $this->presenter->getData());
    }

    public function testPresentThrowsExceptionForUnsupportedOutputInterface(): void
    {
        $outputMock = $this->createMock(OutputInterface::class);

        $this->expectException(PresenterException::class);
        $this->presenter->present($outputMock);
    }
}
