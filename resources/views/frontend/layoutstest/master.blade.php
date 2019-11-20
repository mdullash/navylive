<!DOCTYPE html>
<html>
<body>
@include('frontend.layouts._head')
@include('frontend.layouts._header')

        @yield('content');

@include('frontend.layouts._footer')
@include('frontend.layouts._scripts')
</body>
</html>