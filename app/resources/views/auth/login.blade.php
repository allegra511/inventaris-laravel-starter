@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="login-card">
    <div class="brand-mark large">I</div>
    <p class="eyebrow">SISTEM INFORMASI INVENTARIS</p>
    <h1>Selamat datang</h1>
    <p class="muted">Masuk untuk mengelola data dan pergerakan barang.</p>
    <form method="POST" action="{{ route('login.post') }}" class="form-stack">
        @csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></label>
        <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
        <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button class="btn btn-primary btn-block">Masuk</button>
    </form>
    <p class="hint">Demo lokal: admin@inventaris.test / password</p>
</div>
@endsection
