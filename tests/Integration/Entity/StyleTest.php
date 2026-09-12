<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Tests\Integration\IntegrationTestCase;

class StyleTest extends IntegrationTestCase
{
    private string $name;
    private int $seq;

    private Style $style;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->style);
        $this->assertSame($this->name, $this->style->name);
        $this->assertSame($this->seq, $this->style->seq);
        $this->assertTrue($this->style->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->style->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->style);

        $seq = 2;
        $created = $this->style->created;

        $this->style->seq = $seq;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->style);
        $this->assertSame($seq, $this->style->seq);
        $this->assertSame($created->getTimestamp(), $this->style->created->getTimestamp());
        $modified = $this->style->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeUpdateTs <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->style);

        $this->assertCount(0, $this->style->styleImgs);
        $styleImg = $this->dbService->createStyleImg($this->em, $this->style, 1);
        $this->style->addStyleImg($styleImg);
        $this->assertCount(1, $this->style->styleImgs);
        $this->style->removeStyleImg($styleImg);
        $this->assertCount(0, $this->style->styleImgs);

        $this->assertCount(0, $this->style->styleInfoBottoms);
        $styleInfoBottom = $this->dbService->createStyleInfoBottom($this->em, $this->style, $this->fixtureService->getRandomString(), 1);
        $this->style->addStyleInfoBottom($styleInfoBottom);
        $this->assertCount(1, $this->style->styleInfoBottoms);
        $this->style->removeStyleInfoBottom($styleInfoBottom);
        $this->assertCount(0, $this->style->styleInfoBottoms);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();
        $this->seq = 1;

        $this->style = $this->dbService->createStyle($this->em, $this->name, $this->seq);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteStyle($this->em, $this->style->id);
    }
}
