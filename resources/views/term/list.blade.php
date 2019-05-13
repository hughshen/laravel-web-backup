@extends('layouts.main')

@section('menu', '1')

@section('title', $heading)

@section('heading', $heading)

@section('content')
    @if ($terms)

        @if ($tax == 'category')

        <ul class="categories">

            @foreach ($terms as $term)
                <li class="item">
                    <a href="{{ route('term.cat.show', ['slug' => $term->slug]) }}">{{ $term->name }}</a>
                </li>
            @endforeach

        </ul>

        @elseif ($tax == 'tag')

        <ul class="tags">

            @foreach ($terms as $term)
                <li class="item">
                    <a href="{{ route('term.tag.show', ['slug' => $term->slug]) }}">#{{ $term->name }}</a>
                </li>
            @endforeach

        </ul>

        @else

        <div class="site-empty">
            <h2>Nothing found.</h2>
        </div>

        @endif

    @else

        <div class="site-empty">
            <h2>Nothing found.</h2>
        </div>

    @endif

@endsection