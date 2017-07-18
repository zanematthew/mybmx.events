@extends('layouts.app')

@section('content')
<div class="masthead row">
    <div class="top row">
        <primary-nav :items="primaryNav"></primary-nav>
    </div>
</div>
<router-view></router-view>
@endsection
