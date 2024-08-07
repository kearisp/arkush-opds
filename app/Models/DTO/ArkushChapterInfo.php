<?php namespace App\Models\DTO;

class ArkushChapterInfo {
    public ?string $title = null;
    public ?string $href = null;
    /**
     * @var string[]
     */
    public array $paragraphs = [];
}

