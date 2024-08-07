<?php namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\DTO\ArkushBook;
use App\Models\DTO\ArkushChapterInfo;
use App\Models\DTO\ArkushAuthor;


class ArkushService {
    protected function getBookXPath(int $id): \DOMXPath {
        $filePath = "$id.html";

        if(!Storage::fileExists($filePath)) {
            $res = Http::get("https://arkush.net/book/$id");
            $body = $res->body();

            Storage::put($filePath, $body);
        }
        else {
            $body = Storage::get($filePath);
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($body);
        libxml_clear_errors();

        return new \DOMXPath($doc);
    }

    protected function getChapterXPath(int $bookId, int $chapterIndex): \DOMXPath {
        $filePath = "$bookId/$chapterIndex.html";

        if(!Storage::fileExists($filePath)) {
            $res = Http::get("https://arkush.net/book/$bookId/$chapterIndex");
            $body = $res->body();

            Storage::put($filePath, $body);
        }
        else {
            $body = Storage::get($filePath);
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($body);
        libxml_clear_errors();

        return new \DOMXPath($doc);
    }

    public function getBook(int $id) {
        $xpath = $this->getBookXPath($id);
        $book = new ArkushBook();

        $book->id = $id;
        $title = $xpath->query("//h1[contains(@class, 'name')]");

        if($title) {
            $book->title = $title->item(0)->textContent;
        }

        $annotation = $xpath->query("//div[contains(@class, 'annotation')]");

        if($annotation) {
            $book->description = $annotation->item(0)->textContent;
        }

        $authorsDom = $xpath->query("//a[contains(@class, 'author')]/span[contains(@class, 'name')]");

        if($authorsDom) {
            /**
             * @var \DOMElement $author
             */
            foreach($authorsDom as $authorDom) {
                $author = new ArkushAuthor();

                foreach($authorDom->childNodes as $childNode) {
                    if($childNode->nodeType === XML_TEXT_NODE) {
                        $name = explode(' ', $childNode->textContent);

                        $author->firstName = $name[0] ?? '';
                        $author->lastName = $name[1] ?? '';
                    }
                }

                $book->authors[] = $author;
            }
        }

        $covers = $xpath->query("//div[contains(@class, 'cover-wrapper')]/img");

        if($covers && $covers->count() > 0) {
            $cover = $covers->item(0);

            $book->image = $cover->getAttribute('src');
        }

        $elements = $xpath->query("//div[contains(@class, 'part')]/a");

        if($elements) {
            /**
             * @var $element \DOMElement
             */
            foreach($elements as $element) {
                $chapter = new ArkushChapterInfo();

                $chapter->title = $element->textContent;
                $chapter->href = $element->getAttribute('href');

                $book->chapters[] = $chapter;
            }
        }

        return $book;
    }

    public function getBookChapter(ArkushBook $book, int $chapterIndex) {
        $xpath = $this->getChapterXPath($book->id, $chapterIndex);

        $paragraphs = $xpath->query("//div[contains(@class, 'book-content')]/*");

        if($paragraphs) {
            /**
             * @var \DOMElement $paragraphNode
             */
            foreach($paragraphs as $paragraphNode) {
                $paragraph = '';

                foreach($paragraphNode->childNodes as $childNode) {
                    $paragraph .= $xpath->document->saveHTML($childNode);
                }

                $book->chapters[$chapterIndex - 1]->paragraphs[] = $paragraph;
            }
        }
    }

    public function getBookCover(string $path) {
        $res = Http::get("https://arkush.net$path");

        return $res->body();
    }
}
