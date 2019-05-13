@extends('layouts.main')

@section('allow_index', '1')

@section('title', $post->title)

@section('content')

    <article class="post">
        <header>
            <a href="{{ route('home') }}" class="back">
                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h48v48h-48z" fill="none"/><path d="M40 22h-24.34l11.17-11.17-2.83-2.83-16 16 16 16 2.83-2.83-11.17-11.17h24.34v-4z" fill="white"/></svg>
            </a>
            <h1 class="posttitle">{{ $post->title }}</h1>
            <div class="meta">
                <div class="postdate">
                    <time datetime="{{ date('c', strtotime($post->created_at)) }}" itemprop="datePublished">{{ date('Y M d, H:i:s ', strtotime($post->created_at)) }}</time>
                </div>

                @foreach ($post->cats as $cat)
                    <div class="article-term article-cat">
                        <a class="term-link cat-link" href="{{ route('term.cat.show', ['slug' => $cat->slug]) }}">{{ $cat->name }}</a>
                    </div>
                @endforeach

                @foreach ($post->tags as $tag)
                    <div class="article-term article-tag">
                        <a class="term-link tag-link" href="{{ route('term.tag.show', ['slug' => $tag->slug]) }}">#{{ $tag->name }}</a>
                    </div>
                @endforeach

            </div>
        </header>

        <div class="content">{!! app('markdown')->process($post->content) !!}</div>
    </article>

@endsection