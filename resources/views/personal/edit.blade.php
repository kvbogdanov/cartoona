@extends('layout.personal')
@section('content')
    <div class="container ">
        @if(!empty($errors) && ($errors != '[]'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <form class="col-12" action="" method="POST">
                {{ csrf_field() }}
                @foreach($template->frames as $frame)
                    @if(!$frame->usereditable)
                        @continue
                    @endif
                    @php
                        $mediaItems = $frame->getMedia();
                    @endphp
                <div class="form-group row">
                    <label class="col-sm-1 col-form-label" for="usermail">{{ ($frame->orderInList()) }}</label>
                    <div class="col-sm-3">
                    @foreach($mediaItems as $image)
                        <img src="{{ $image->getUrl('thumb') }}">
                    @endforeach
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control col-sm-12" name="frameheader[{{ ($frame->id_frame) }}]" value="{{ $frame->userheader($id_card) }}">
                        <textarea  class="form-control col-sm-12" name="frametext[{{ ($frame->id_frame) }}]" rows="6">{{ strip_tags($frame->usertext($id_card)) }}</textarea>
                    </div>
                </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="/personal/cards">Cancel</a>
            </form>
        </div>

    </div>
@stop