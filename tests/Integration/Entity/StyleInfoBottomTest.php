<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Entity\StyleInfoBottom;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;

class StyleInfoBottomTest extends IntegrationTestCase
{
    private string $name;
    private int $seq;

    private Style $style;
    private StyleInfoBottom $styleInfoBottom;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleInfoBottom);
        $this->assertSame($this->style->id, $this->styleInfoBottom->style->id);
        $this->assertSame($this->name, $this->styleInfoBottom->name);
        $this->assertSame($this->seq, $this->styleInfoBottom->seq);
        $this->assertTrue($this->styleInfoBottom->created->getTimestamp() <= $beforeModify);
        $this->assertNull($this->styleInfoBottom->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleInfoBottom);

        $text = TestHelper::getRandomString();
        $created = $this->styleInfoBottom->created;

        $this->styleInfoBottom->text = $text;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleInfoBottom);
        $this->assertSame($text, $this->styleInfoBottom->text);
        $this->assertSame($created->getTimestamp(), $this->styleInfoBottom->created->getTimestamp());
        $this->assertNotNull($this->styleInfoBottom->modified);
        $this->assertTrue($beforeModify <= $this->styleInfoBottom->modified->getTimestamp());
        $this->assertTrue($this->styleInfoBottom->modified->getTimestamp() <= $afterModify);
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();
        $this->seq = 1;

        $this->style = DBTestHelper::createStyle($this->em, TestHelper::getRandomString(), 1);
        $this->styleInfoBottom = DBTestHelper::createStyleInfoBottom($this->em, $this->style, $this->name, $this->seq);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteStyle($this->em, $this->style->id);
    }
}
