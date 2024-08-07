@php
/**
 * @var \App\Models\DTO\ArkushBook[] $books
 */
    echo '<?xml version="1.0" encoding="utf-8"?>';
@endphp
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>{{ url('opds/root.xml') }}</id>
    <link
      rel="self"
      href="{{ url('opds/root.xml') }}"
      type="application/atom+xml;profile=opds-catalog;kind=navigation" />
    <link
      rel="start"
      href="{{ url('opds/root.xml') }}"
      type="application/atom+xml;profile=opds-catalog;kind=navigation" />

    <title>OPDS Arkush Catalog</title>
    <updated>2010-01-10T10:03:10Z</updated>
@foreach($books as $book)
    <entry>
        <title>{{ $book->title }}</title>
@foreach($book->authors as $author)
        <author>
            <name>{{ $author->firstName }} {{ $author->lastName }}</name>
        </author>
@endforeach
        <id>{{ $book->id }}</id>
        <updated>2024-08-06T12:00:00Z</updated>
        <link
          rel="http://opds-spec.org/acquisition"
          type="application/x-fictionbook+xml"
          href="{{ url("book/$book->id.fb2") }}" />
        <link
          rel="alternate"
          type="text/html"
          href="https://arkush.net/book/{{ $book->id }}" />
@if($book->image)
        <link
          rel="http://opds-spec.org/image"
          href="{{ url("https://arkush.net$book->image") }}" />
@endif
        <content type="text">{{ $book->description }}</content>
    </entry>
@endforeach
</feed>
