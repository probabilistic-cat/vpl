<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Field\IdField;
use App\Entity\Field\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'style_info_bottom')]
#[ORM\Index(name: 'ix__style_info_b__style_id', columns: ['style_id'])]
class StyleInfoBottom
{
    use IdField;
    use TimestampFields;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Style::class, cascade: ['persist'], inversedBy: 'styleInfoBottoms')]
    #[ORM\JoinColumn(name: 'style_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Style $style;
}
