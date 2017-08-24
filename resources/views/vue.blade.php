@extends('layouts.app')

@section('content')
<div class="masthead row">
    <div class="top row">
        <user></user>
        <primary-nav></primary-nav>
    </div>
</div>
<router-view></router-view>
@endsection
