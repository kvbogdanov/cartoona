@extends('layout.personal')
@section('content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col col-6">
                    <h3>Welcome!</h3>
                    <p>Your password is: <b>{{ $password }}</b></p>
                    <a href="/personal/edit/{{ $id_template }}" class="btn btn-primary">Proceed to edit</a>
                </div>
            </div>
        </div>
    </div>
@stop