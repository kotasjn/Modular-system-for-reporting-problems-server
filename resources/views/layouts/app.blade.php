<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @include('layouts.partials.head')

</head>

<body id="page-top">


@include('layouts.partials.nav')

<div id="wrapper">

  @include('layouts.partials.sidebar')

  <div id="content-wrapper" class="">
    @include('layouts.partials.content')
    @include('layouts.partials.footer')
  </div>

</div>

@include('layouts.partials.footer-scripts')

</body>

</html>
