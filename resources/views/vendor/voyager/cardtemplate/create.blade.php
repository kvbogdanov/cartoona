@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@php
    if(!empty($dataTypeContent->url))
        $url = '/' . $dataTypeContent->url;
    else
        $url = '/cart/' . $dataTypeContent->id_cardtemplate;
@endphp


@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
        </h1>

        <a href="/admin/frame/{{ $dataTypeContent->id_cardtemplate }}/create" class="btn btn-success btn-add-new"><i class="voyager-plus"></i> <span>Add New Frame</span></a>
        <a class="btn-sm btn-warning" href="#settings">to settings</a>
        <a class="btn-sm btn-info" href="{{ $url }}" target=_blank>Open in new tab</a>
        <a href="/admin/cardtemplate/{{ $dataTypeContent->id_cardtemplate }}/duplicate" class="btn btn-danger btn-add-new"><i class="voyager-plus"></i> <span>Duplicate this card</span></a>
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('javascript')
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $("#uploadFile").change(function() {

            $('#image_preview').html("");

            var total_file = document.getElementById("uploadFile").files.length;

            for (var i = 0; i < total_file; i++)
            {
                $('#image_preview').append("<img style='max-width:300px;' src='" + URL.createObjectURL(event.target.files[i]) + "'>");
            }

        });

        $(function() {
            $( "#framesort" ).sortable({
              update: function( event, ui ) {
                $("#framesortInput").val($( "#framesort" ).sortable( "toArray" ));
              }
            });
            $( "#framesort" ).disableSelection();

            $('.delete').click(function(){
                $('#delete_modal').modal();
                let fid =  parseInt($(this).attr('data-id'));
                $('#fid').val(fid);
            });

            $('#deleteImage').on('click', function(){
                $('#dropimage').val(1);
                $('#image_preview').html('');
                return false;
            });
        });
    </script>
@stop

@section('content')
    <div class="page-content browse container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

            		<form method="POST" class="form-edit-add"
                            action="/admin/cardtemplate/{{ $dataTypeContent->getKey() }}/update"
                            method="POST" enctype="multipart/form-data">
            		    {{ csrf_field() }}
                        <input type="hidden" name="dropimage" value="0" id="dropimage"/>
                        <div style="padding: 10px;">
                            <h4>Cover</h4>
                            <input type="file" id="uploadFile" name="uploadFile[]"/>
                            <div id="image_preview">
                                @php
                                    $mediaItems = $dataTypeContent->getMedia();
                                @endphp
                                @foreach($mediaItems as $image)
                                    <img src="{{ $image->getUrl('thumb') }}" style="border-width: {{ $dataTypeContent->border }}px; border-color: {{ $dataTypeContent->border_color }}; border-style: solid" ;>
                                    <a href="javascript:" id="deleteImage" class="btn btn-danger">Delete image</a>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="framesort" id="framesortInput">

                        <!--select multiple>
                            <?php
                            /*foreach ($variable as $key => $value) {
                                # code...
                            }*/?>
                        </select-->

                        <h4 style="padding:10px;">Frames</h4>
                        <ul id="framesort">
                        @foreach($dataTypeContent->frames as $frame)
                            <li style="min-height: 30px;" id="f{{ $frame->getKey() }}">
                                <div class="wrapper">
                                @php
                                   $mediaItems = $frame->getMedia();
                                @endphp
                                    <div class="frame-number"><span>{{ ($frame->orderInList()) }}</span></div>
                                    <div class="frame-preview">
                                        <iframe src="{{ url('/') }}/{{ $dataTypeContent->url }}#{{ $frame->id_frame }}" scrolling="no"></iframe>
                                    </div>
<!--
                                    <div class="frame-image">
                                @foreach($mediaItems as $image)
                                        <img src="{{ $image->getUrl('thumb') }}">
                                @endforeach
                                    </div>
                                    <div class="frame-info">
                                        <h3>{{ $frame->header }}</h3>
                                        <div class="frame-desc">  {!!  $frame->text !!} </div>
                                    </div>
                                    <div class="frame-settings">
                                        <ul>
                                            <li>Visible: {{ $frame->visible?'yes':'no' }}</li>
                                            <li>Effect: {{ $frame->effect?$frame->effect:'no' }}</li>
                                        </ul>
                                    </div>
-->
                                </div>
                                <div class="frame-buttons">
                                    <a href="/admin/frame/{{ $frame->id_frame }}/edit" title="Edit" class="btn btn-sm btn-primary edit"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Edit</span></a>
                                    <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger delete" data-id="{{ $frame->id_frame }} " id="delete-{{ $frame->id_frame }} "><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Delete</span></a>
                                    <a href="{{ $url }}#{{ $frame->id_frame }}" class="btn btn-success btn-frame-preview" target="_blank"><i class="voyager-tv"></i> <span>Preview Frame</span></a>
                                </div>
                            </li>
                        @endforeach
                        </ul>

                        <div id="settings"></div>

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
                            <a class="btn-sm btn-info" href="{{ $url }}" target=_blank>Open in new tab</a>
                        </div>

            		</form>

                </div>
            </div>
        </div>

	</div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i>Delete frame?</h4>
                </div>
                <div class="modal-footer">
                    <form action="/admin/cardtemplate/{{ $dataTypeContent->getKey() }}/deleteframe" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="hidden" id="fid" name="frame" value="0">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop


