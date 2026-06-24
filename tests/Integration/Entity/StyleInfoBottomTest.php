<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Entity\StyleInfoBottom;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StyleInfoBottomTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Style $style;
    private StyleInfoBottom $styleInfoBottom;

    public function testStyleInfoBottom(): void {
        $beforeModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $styleInfoBottom = $this->em->getRepository(StyleInfoBottom::class)->find($this->styleInfoBottom->id);
        $this->assertSame($this->style->id, $styleInfoBottom->style->id);
        $this->assertSame($this->styleInfoBottom->name, $styleInfoBottom->name);
        $this->assertSame($this->styleInfoBottom->seq, $styleInfoBottom->seq);
        $this->assertTrue($styleInfoBottom->created->getTimestamp() <= $beforeModify);
        $this->assertNull($styleInfoBottom->modified);

        $styleInfoBottom->text = TestHelper::getRandomString();
        $this->em->persist($styleInfoBottom);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $styleInfoBottom2 = $this->em->getRepository(StyleInfoBottom::class)->find($this->styleInfoBottom->id);
        $this->assertSame($styleInfoBottom->text, $styleInfoBottom2->text);
        $this->assertEquals($styleInfoBottom->created, $styleInfoBottom2->created);
        $this->assertNotNull($styleInfoBottom2->modified);
        $this->assertTrue($beforeModify <= $styleInfoBottom2->modified->getTimestamp());
        $this->assertTrue($styleInfoBottom2->modified->getTimestamp() <= $afterModify);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, 1);
        $this->styleInfoBottom = DBTestHelper::createStyleInfoBottom($this->em, $this->style, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
        $this->em = null;
    }
}
