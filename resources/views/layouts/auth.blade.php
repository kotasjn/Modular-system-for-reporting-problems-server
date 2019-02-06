<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @include('layouts.partials.head')

</head>

<body id="bg-color">

<div id="wrapper">

    <div id="content-wrapper-auth">
        @include('layouts.partials.content')
    </div>

</div>

@include('layouts.partials.footer-scripts')

</body>

</html>