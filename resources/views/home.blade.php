@extends('layouts.app')

@section('home')
<div id="main">
    @include('includes.menu_bar')
    @yield('content')
</div>
@endsection
