@extends('layouts.auth')

@section('content')

    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">{{ __('Registrace') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">

                    @csrf

                    @if(isset($avatar))

                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <img src="{{$avatar}}" alt="Avatar" class="avatar"/>
                            </div>
                        </div>

                        <input id="avatar" type="hidden" name="avatar" value="{{$avatar}}">

                    @endif

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="name"
                                   class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                   placeholder="Jméno" required="required" value="{{ $name ?? old('name') }}"
                                   autofocus="autofocus">
                            <label for="name">Jméno</label>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" id="email"
                                   class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   placeholder="Email" required="required" value="{{ $email ?? old('email') }}">
                            <label for="email">Email</label>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group input-group">

                            <div class="input-group-addon text-md-center" style="width: 15%; padding: 12px 0 12px 0;">+420</div>
                            <div class="form-label-group" style="width: 85%;">
                                <input type="text" id="telephone"
                                       class="form-control {{ $errors->has('telephone') ? ' is-invalid' : '' }}" style="border-radius: 0.25rem"
                                       name="telephone"
                                       placeholder="Telefon" required="required" value="{{ old('telephone') }}">
                                <label for="telephone">Telefon</label>
                            </div>

                            @if ($errors->has('telephone'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="password"
                                   class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" placeholder="Heslo" required="required">
                            <label for="password">Heslo</label>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="password_confirmation"
                                   class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                   name="password_confirmation" placeholder="Heslo (potvrzení)" required="required">
                            <label for="password_confirmation">Heslo (potvrdit)</label>

                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-shadow" style="margin: 2em 0 0 0;">
                            {{ __('Registrovat') }}
                        </button>
                    </div>

                </form>

                @if(!isset($name))

                    <div class="form-group row" style="margin-top: 1em;">

                        <label for="name" class="col-md-12 col-form-label text-center">nebo</label>

                    </div>

                    <div class="form-group row">

                        <div class="col-md-12 text-center">

                            <a href="{{ url('login/facebook') }}" class="fa fa-facebook btn-shadow"></a>

                            <a href="{{ url('login/google') }}" class="fa fa-google btn-shadow"></a>

                        </div>

                    </div>

                @endif

                <div class="form-group text-center" style="margin: 2em 0 0 0;">
                    Už máš účet? <a href="/login">Přihlaš se!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
