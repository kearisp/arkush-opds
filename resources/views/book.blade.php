@php
/**
 * @var \App\Models\DTO\ArkushBook $book
 */
    echo '<?xml version="1.0" encoding="utf-8" ?>';
@endphp

<FictionBook
  xmlns:l="http://www.w3.org/1999/xlink"
  xmlns:xlink="http://www.w3.org/1999/xlink"
  xmlns="http://www.gribuser.ru/xml/fictionbook/2.0">
    <description>
        <title-info>
@foreach($book->authors as $author)
            <author>
                <first-name>{{ $author->firstName }}</first-name>
                <last-name>{{ $author->lastName }}</last-name>
            </author>
@endforeach
            <book-title>{{ $book->title }}</book-title>
            <annotation>{{ $book->description }}</annotation>
        </title-info>
    </description>
    <body>
        <section>
            <title><p>{{ $book->title }}</p></title>
@foreach($book->authors as $author)
            <p><strong>{{ $author->firstName }} {{$author->lastName}}</strong></p>
@endforeach
            <p>{{ $book->description }}</p>
        </section>
@foreach($book->chapters as $chapter)
        <section>
            <title><p>{{ $chapter->title }}</p></title>
            <empty-line />
@foreach($chapter->paragraphs as $paragraph)
@if($paragraph)
            <p>{!! $paragraph !!}</p>
@else
            <empty-line />
@endif
@endforeach
        </section>
@endforeach
    </body>
</FictionBook>
