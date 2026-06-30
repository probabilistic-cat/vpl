<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
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
        $this->assertNotNull($this->style->modified);
        $this->assertTrue($beforeUpdateTs <= $this->style->modified->getTimestamp());
        $this->assertTrue($this->style->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->style);

        $this->assertSame(0, $this->style->styleImgs->count());
        $styleImg = DBTestHelper::createStyleImg($this->em, $this->style, 1);
        $this->style->addStyleImg($styleImg);
        $this->assertSame(1, $this->style->styleImgs->count());
        $this->style->removeStyleImg($styleImg);
        $this->assertSame(0, $this->style->styleImgs->count());

        $this->assertSame(0, $this->style->styleInfoBottoms->count());
        $styleInfoBottom = DBTestHelper::createStyleInfoBottom($this->em, $this->style, TestHelper::getRandomString(), 1);
        $this->style->addStyleInfoBottom($styleInfoBottom);
        $this->assertSame(1, $this->style->styleInfoBottoms->count());
        $this->style->removeStyleInfoBottom($styleInfoBottom);
        $this->assertSame(0, $this->style->styleInfoBottoms->count());
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();
        $this->seq = 1;

        $this->style = DBTestHelper::createStyle($this->em, $this->name, $this->seq);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteStyle($this->em, $this->style->id);
    }
}
