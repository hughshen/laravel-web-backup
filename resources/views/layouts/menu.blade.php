<header id="header">
    <div id="title">
        <h1>{{ $config['site_name'] }}</h1>
    </div>
    <div id="nav">
        <ul>
            <li><a href="{{ route('home') }}">首页</a></li>
            <li><a href="{{ route('term.cats')  }}">分类</a></li>
            <li><a href="{{ route('term.tags') }}">标签</a></li>
        </ul>
    </div>
</header>
<section id="search">
    <span class="h1">Search</span>
    <form id="search-form" action="{{ route('site.search') }}" method="get">
        <div class="search-input">
            <input type="text" name="s" value="{{ request()->input('s', '') }}">
        </div>
        <div class="search-button">
            <button type="submit" class="ui-button ui-button-sgreen">&nbsp;搜索&nbsp;</button>
        </div>
    </form>
</section>