<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StyleTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Style $style;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $style = $this->em->getRepository(Style::class)->find($this->style->id);
        $this->assertSame($this->style->name, $style->name);
        $this->assertSame($this->style->seq, $style->seq);
        $this->assertTrue($style->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($style->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $style = $this->em->getRepository(Style::class)->find($this->style->id);

        $seq = 2;
        $created = $style->created;

        $style->seq = $seq;
        $this->em->persist($style);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $style2 = $this->em->getRepository(Style::class)->find($this->style->id);
        $this->assertSame($seq, $style2->seq);
        $this->assertSame($created->getTimestamp(), $style2->created->getTimestamp());
        $this->assertNotNull($style2->modified);
        $this->assertTrue($beforeUpdateTs <= $style2->modified->getTimestamp());
        $this->assertTrue($style2->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
        $this->em = null;
    }
}
