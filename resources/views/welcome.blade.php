@extends('layouts.main')

@if (isset($allowIndex) && $allowIndex === true)

@section('allow_index', '1')

@endif

@section('menu', '1')

@if (isset($heading) && !empty($heading))

@section('title', $heading)

@endif

@section('content')
    @if ($posts)
        <section id="writing">
            <span class="h1">
                {{ (isset($heading) && !empty($heading) ? $heading : 'Archives') }}
            </span>
            <ul class="post-list">
                @foreach ($posts as $post)
                    <li class="post-item">
                        <div class="meta">
                            <time datetime="{{ date('c', strtotime($post->created_at)) }}" itemprop="datePublished">
                                {{ date('Y M d', strtotime($post->created_at)) }}
                            </time>
                        </div>
                        <span>
                            <a href="{{ route('post.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
        {{ $posts->links() }}
    @else
        <div class="site-empty">
            <h2>Nothing found.</h2>
        </div>
    @endif
@endsection