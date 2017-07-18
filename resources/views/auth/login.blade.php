@extends('layouts.app')

@section('title', '| Login')
@section('body_class', 'login')

@section('content')
<div class="box">
    <center>Logo Here</center>
    <div class="main">
        <form class="form" role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="row">
            @if ($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
            @if ($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
            </div>

            <div class="row icon-row">
                <span class="input-field-icon"><icon name="envelope"></icon></span>
                <input id="email" type="email" name="email" placeholder="E-mail..." value="{{ old('email') }}" required autofocus>
            </div>

            <div class="row icon-row">
                <span class="input-field-icon"><icon name="lock"></icon></span>
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="row">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
    <ul class="mini-nav nav">
        <li><a href="{{ route('register') }}">Register</a></li>
        <li><a href="{{ route('password.request') }}">Forgot Your Password?</a></li>
    </ul>
</div>
@endsection
