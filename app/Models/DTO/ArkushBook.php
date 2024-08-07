<?php namespace App\Models\DTO;

class ArkushBook {
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?string $cover = null;
    public ?string $image = null;
    public ?string $imageType = null;

    /**
     * @var ArkushAuthor[]
     */
    public array $authors = [];

    /**
     * @var ArkushChapterInfo[]
     */
    public array $chapters = [];
}
