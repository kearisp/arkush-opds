<?php namespace App\Http\Controllers\Pages;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\ArkushService;


class OpdsController extends Controller {
    public function __construct(
        protected readonly ArkushService $arkushService
    ) {}

    public function root(): Response {
        $ids = [19210, 19246, 19737];

        $books = [];

        foreach($ids as $id) {
            $books[] = $this->arkushService->getBook($id);
        }

        return response()
            ->view('opds.root', [
                'books' => $books,
            ])
            ->withHeaders([
                'Content-Type' => 'text/xml'
            ]);
    }
}
