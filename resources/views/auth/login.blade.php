@extends('/layouts/layout')

{{--start section--}}
@section('content')


    <div class="container" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login Form</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">ID</label>

                                <div class="col-md-6">
                                    <input id="ID" type="text" class="form-control{{ $errors->has('AuthProblem') ? ' is-invalid' : '' }}" name="ID" value="{{ old('ID') }}" required autofocus placeholder="ID example 000XXXX000">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('AuthProblem') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('AuthProblem'))
                                        <span class="invalid-feedback text-center">
                                            <strong>{{ $errors->first('AuthProblem') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 text-center" >
                                <div class="col-md-8" style="margin: auto;">
                                    <button type="submit" class="btn btn-block btn-info">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="#">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{--end section--}}
