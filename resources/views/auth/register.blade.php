@extends('layouts.app')

@section('title', '| Register')
@section('body_class', 'login')

@section('content')
<div class="box">
    <center>Logo Here</center>
    <p><a href="/redirect/facebook">Login with Facebook</a></p>
    <div class="main">
        <form class="form" role="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="row">
                {{ $errors->has('name') ? ' has-error' : '' }}
                {{ $errors->has('email') ? ' has-error' : '' }}
                {{ $errors->has('password') ? ' has-error' : '' }}
                @if ($errors->has('name'))
                {{ $errors->first('name') }}
                @endif
                @if ($errors->has('email'))
                {{ $errors->first('email') }}
                @endif
                @if ($errors->has('password'))
                {{ $errors->first('password') }}
                @endif
            </div>

            <div class="row icon-row">
                <span class="input-field-icon"><icon name="user"></icon></span>
                <input id="name" type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="row icon-row">
                <span class="input-field-icon"><icon name="envelope"></icon></span>
                <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>
            <div class="row icon-row">
                <span class="input-field-icon"><icon name="lock"></icon></span>
                <input id="password" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="row icon-row">
                <span class="input-field-icon"><icon name="lock"></icon></span>
                <input id="password-confirm" type="password" name="password_confirmation" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-primary">Register</button>
        </form>
    </div>
    <ul class="mini-nav nav">
        <li><a href="{{ route('login') }}">Login</a></li>
    </ul>
</div>
@endsection
