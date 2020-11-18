@extends('layout.personal')
@section('content')
    <div class="container h-100">
        @if(!empty($errors))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-6-sm" action="" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="usermail">Email address</label>
                    <input type="email" class="form-control" id="usermail" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="{{ $email }}">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="g-recaptcha" data-sitekey="6LfoXpEUAAAAAKKh-Oa4bG2klaMEoX4zy8QkvYDR"></div><br>
                <button type="submit" class="btn btn-primary">Proceed</button>
                <a href="/personal/login" class="btn btn-info">I already have an account</a>
            </form>
        </div>
    </div>
@stop