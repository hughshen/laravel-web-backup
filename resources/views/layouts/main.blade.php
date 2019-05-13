<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
@hasSection('title')
    @yield('title') | {{ $config['site_name'] }}
@else
    {{ $config['site_name'] }}
@endif
</title>
@hasSection('allow_index')
@else
<meta name="robots" content="noindex, nofollow">
@endif
<link rel="stylesheet" href="{{ asset_ver('css/app.css') }}">
</head>
<body>
<div class="content index width mx-auto px2 my4">
    @hasSection('menu')
        @include('layouts.menu')
    @endif
    @yield('content')
</div>
<footer id="footer">
    <div class="footer-left">
        {{ $config['site_copyright'] }}. Theme inspired by <a target="_blank" href="https://probberechts.github.io/hexo-theme-cactus/cactus-dark/public/">Cactus Dark</a>.
    </div>
</footer>
@if (!empty($config['site_google_analytics_code']))
{!! $config['site_google_analytics_code'] !!}
@endif
</body>
</html>