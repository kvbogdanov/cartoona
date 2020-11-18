@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_singular }} # {{ $dataTypeContent->orderInList() }}
        </h1>
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
    $("#uploadFile").change(function() {

        $('#image_preview').html("");

        var total_file = document.getElementById("uploadFile").files.length;

        for (var i = 0; i < total_file; i++)
        {
            $('#image_preview').append("<img style='max-width:300px;' src='" + URL.createObjectURL(event.target.files[i]) + "'>");

            // hope we have only one img!
            document.getElementById("preview").contentWindow.document.getElementsByTagName("img")[0].src = URL.createObjectURL(event.target.files[i]);
        }

    });

    $(document).ready(function(){
        if($("iframe").length)
        {
            $('.form-group:eq(1),.form-group:eq(2)').hide();


            $("form").on('submit', function(){
                let content = document.getElementById("preview").contentWindow.document.getElementById("content{{ $dataTypeContent->id_frame }}").innerHTML.trim(),
                    header = document.getElementById("preview").contentWindow.document.getElementById("header").innerHTML.trim();

                if(!$('.form-group:eq(1)').is(":visible"))
                    $("input[type=text]")[0].value = header;
                if(!$('.form-group:eq(2)').is(":visible"))
                    $("textarea").text(content);

                return true;
            });
        }

        $("iframe").on('load', function(){
            document.getElementById("preview").contentWindow.document.getElementById("header").setAttribute('contenteditable', true);
            document.getElementById("preview").contentWindow.document.getElementById("content{{ $dataTypeContent->id_frame }}").setAttribute('contenteditable', true);

            if(document.getElementById("preview").contentWindow.document.getElementById("header").innerHTML.trim() == '')
                $('.form-group:eq(1)').show();
            if(document.getElementById("preview").contentWindow.document.getElementById("content{{ $dataTypeContent->id_frame }}").innerHTML.trim() == '')
                $('.form-group:eq(2)').show();

        });


        $('#deleteImage').on('click', function(){
            $('#dropimage').val(1);
            $('#image_preview').html('');
            document.getElementById("preview").contentWindow.document.getElementsByTagName("img")[0].src = '';
            return false;
        });

        $("#audio_upload a.btn-danger").click(function(){
            $(this).parent().remove();
            return false;
        })
    });
    </script>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-8">

                <div class="panel panel-bordered">

                    @if($dataTypeContent->text!='' || $dataTypeContent->header!='')
                    <div class="single-frame-preview">
                        <iframe src="{{ url('/') }}/{{ $dataTypeContent->cardtemplate->url }}#{{ $dataTypeContent->id_frame }}" scrolling="no" id="preview"></iframe>
                    </div>
                    @endif

            		<form method="POST" class="form-edit-add"
                            action="/admin/frame/{{ $dataTypeContent->getKey() }}/update"
                            method="POST" enctype="multipart/form-data">
            		    {{ csrf_field() }}

                        <input type="hidden" name="cardtemplate" value="{{ $dataTypeContent->id_cardtemplate }}"/>
                        <input type="hidden" name="dropimage" value="0" id="dropimage"/>

                        <div style="padding: 10px;">
                            <h2>Upload image</h2>
                            <input type="file" id="uploadFile" name="uploadFile[]"/>
                            <div id="image_preview">
                            @php
                                $mediaItems = $dataTypeContent->getMedia();
                            @endphp
                            @foreach($mediaItems as $image)
                                <img src="{{ $image->getUrl('thumb') }}" style="border-width: {{ $dataTypeContent->border }}px; border-color: {{ $dataTypeContent->border_color }}; border-style: solid;">
                                <a href="javascript:" id="deleteImage" class="btn btn-danger">Delete image</a>
                            @endforeach
                            </div>
                        </div>

                        <div style="padding: 10px;">
                            <h2>Upload audio</h2>
                            <input type="file" id="audioFile" name="uploadAudio[]" multiple/>
                            <br/>
                            <div id="audio_upload">
                                @php
                                    $mediaItems = $dataTypeContent->getMedia('audio');
                                @endphp
                                @foreach($mediaItems as $media)
                                    <div class="audio">
                                        <input type="hidden" name="audio_order[]" >
                                        <input type="hidden" name="audio_ids[]" value="{{$media->id}}">
                                        <audio controls style="vertical-align: bottom;">
                                            <source src="{{$media->getUrl()}}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <a href="javascrip:" class="btn btn-danger">Delete</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        @php
                            $dataTypeRows = $dataType->{(!is_null($dataTypeContent->getKey()) ? 'editRows' : 'addRows' )};
                        @endphp

                        @foreach($dataTypeRows as $row)
                            <!-- GET THE DISPLAY OPTIONS -->
                            @php
                                $display_options = isset($row->details->display) ? $row->details->display : NULL;
                            @endphp
                            @if (isset($row->details->legend) && isset($row->details->legend->text))
                                <legend class="text-{{isset($row->details->legend->align) ? $row->details->legend->align : 'center'}}" style="background-color: {{isset($row->details->legend->bgcolor) ? $row->details->legend->bgcolor : '#f0f0f0'}};padding: 5px;">{{$row->details->legend->text}}</legend>
                            @endif
                            @if (isset($row->details->formfields_custom))
                                @include('voyager::formfields.custom.' . $row->details->formfields_custom)
                            @else
                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ isset($display_options->width) ? $display_options->width : 12 }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label for="name">{{ $row->display_name }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                </div>
                            @endif
                        @endforeach


                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        </div>

            		</form>
                </div>
            </div>
        </div>

	</div>
@stop