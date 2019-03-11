@extends('layouts.auth')

@section('content')

    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">{{ __('Přihlášení') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">

                    @csrf

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail"
                                   class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   placeholder="Email" required="required" value="{{ old('email') }}"
                                   autofocus="autofocus">
                            <label for="inputEmail">Email</label>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="inputPassword"
                                   class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" placeholder="Password" required="required">
                            <label for="inputPassword">Heslo</label>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif

                        </div>

                        <div class="text-right">

                            <a class="d-block small" href="/password/reset">Zapomněl jsi heslo?</a>

                        </div>

                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="remember-me"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Zapamatovat si heslo
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-shadow" style="margin: 1em 0 0 0;">
                            {{ __('Přihlásit se') }}
                        </button>
                    </div>

                </form>

                <div class="form-group row" style="margin-top: 1em;">

                    <label for="name" class="col-md-12 col-form-label text-center">nebo</label>

                </div>

                <div class="form-group row">

                    <div class="col-md-12 text-center">

                        <a href="{{ url('login/facebook') }}" class="fa fa-facebook btn-shadow"></a>

                        <a href="{{ url('login/google') }}" class="fa fa-google btn-shadow"></a>

                    </div>

                </div>

                <div class="form-group text-center" style="margin: 2em 0 0 0;">
                    Nemáš účet? <a href="/register">Zaregistruj se!</a>
                </div>

            </div>
        </div>
    </div>
@endsection
