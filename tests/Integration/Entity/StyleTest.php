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
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $style = $this->em->getRepository(Style::class)->find($this->style->getId());
        $this->assertSame($this->style->getName(), $style->getName());
        $this->assertSame($this->style->getSeq(), $style->getSeq());
        $this->assertTrue($style->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($style->getModified());

        $style->setSeq(2);
        $this->em->persist($style);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $style2 = $this->em->getRepository(Style::class)->find($this->style->getId());
        $this->assertSame($style->getSeq(), $style2->getSeq());
        $this->assertEquals($style->getCreated(), $style2->getCreated());
        $this->assertNotNull($style2->getModified());
        $this->assertTrue($beforeModifyTs <= $style2->getModified()->getTimestamp());
        $this->assertTrue($style2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteStyle($this->em, $this->style->getId());
        $this->em->close();
        $this->em = null;
    }
}
