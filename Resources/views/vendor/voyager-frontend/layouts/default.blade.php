@if (class_exists('Theme') && Theme::exists(Theme::get()))
    @include(Theme::get().".layouts.default")
@else
    @include('voyager-frontend::partials.meta')
    @include('voyager-frontend::partials.header')

    <main class="main-content">
        @yield('content')
    </main>

    @include('voyager-frontend::partials.footer')
@endif