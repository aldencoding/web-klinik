@extends('layouts.mantis')
@section('content')
<div class="auth-main">
    <div class="auth-wrapper v3">
        <div class="auth-form">
            <div class="auth-header">
                <a href="#" class="h3">MY KLINIK</a>
            </div>
            <div class="card my-5">
                <form action="{{ route('login.process') }}" method="post">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Login</b></h3>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                class="form-control"
                                placeholder="Email Address"
                                name="email"
                                value="{{ old('email') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                class="form-control"
                                placeholder="Password"
                                name="password"
                                value="{{ old('email') }}">
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="auth-footer row">
                <!-- <div class=""> -->
                <div class="col my-1">
                    <p class="m-0">Copyright © <a href="#">Codedthemes</a></p>
                </div>
                <div class="col my-1">
                    <p class="m-0">Distributed by <a href="https://themewagon.com">ThemeWagon</a></p>
                </div>
                <!-- <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="#">Home</a></li>
                        <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="#">Contact us</a></li>
                    </ul>
                </div> -->
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection