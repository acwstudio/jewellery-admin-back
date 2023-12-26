<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

class CategoryBlueprint
{
    public function __construct(
        private string $title,
        private string $h1,
        private string $description,
        private ?string $metaTitle = null,
        private ?string $metaDescription = null,
        private ?string $metaKeywords = null,
        private ?string $externalId = null,
        private ?string $slug = null
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): CategoryBlueprint
    {
        $this->title = $title;
        return $this;
    }

    public function getH1(): string
    {
        return $this->h1;
    }

    public function setH1(string $h1): CategoryBlueprint
    {
        $this->h1 = $h1;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): CategoryBlueprint
    {
        $this->description = $description;
        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): CategoryBlueprint
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): CategoryBlueprint
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): CategoryBlueprint
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): CategoryBlueprint
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): CategoryBlueprint
    {
        $this->slug = $slug;
        return $this;
    }
}
