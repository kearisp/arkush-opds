<?php namespace App\Http\Controllers\Pages;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\ArkushService;


class Fb2Controller extends Controller {
    public function __construct(
        protected readonly ArkushService $arkushService
    ) {}

    public function book(int $id): Response {
        $book = $this->arkushService->getBook($id);

        foreach($book->chapters as $index => $chapter) {
            $this->arkushService->getBookChapter($book, $index + 1);
        }

        return response()
            ->view('book', [
                'book' => $book
            ])
            ->withHeaders([
                'Content-Type' => 'application/x-fictionbook+xml'
            ]);
    }
}
