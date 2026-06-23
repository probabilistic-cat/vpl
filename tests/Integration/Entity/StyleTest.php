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

    public function testStyle(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $style = $this->em->getRepository(Style::class)->find($this->style);
        $this->assertSame($this->style->name, $style->name);
        $this->assertSame($this->style->seq, $style->seq);
        $this->assertTrue($style->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($style->getModified());

        $style->seq = 2;
        $this->em->persist($style);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $style2 = $this->em->getRepository(Style::class)->find($this->style);
        $this->assertSame($style->seq, $style2->seq);
        $this->assertEquals($style->getCreated(), $style2->getCreated());
        $this->assertNotNull($style2->getModified());
        $this->assertTrue($beforeModifyTs <= $style2->getModified()->getTimestamp());
        $this->assertTrue($style2->getModified()->getTimestamp() <= $afterModifyTs);
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
