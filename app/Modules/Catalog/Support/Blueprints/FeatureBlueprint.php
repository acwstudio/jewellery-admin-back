<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureBlueprint
{
    public function __construct(
        private FeatureTypeEnum $type,
        private string $value,
        private ?string $slug = null,
        private ?int $position = null,
    ) {
    }

    public function getType(): FeatureTypeEnum
    {
        return $this->type;
    }

    public function setType(FeatureTypeEnum $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;
        return $this;
    }
}
