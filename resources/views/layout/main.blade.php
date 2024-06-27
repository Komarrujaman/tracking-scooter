@include('layout.header')
@include('layout.sidebar')
<div id="content-wrapper" class="d-flex flex-column">
    @yield('content')
    @include('layout.footer')

    @yield('script')

    </body>

    </html>