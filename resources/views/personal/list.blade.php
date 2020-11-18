@extends('layout.personal')
@section('content')
    <div class="container ">

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Url</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                @foreach($cards as $card)
                    @if(is_object($card->cardtemplate))
                        @php
                            $turl = $card->cardtemplate->url??("cart/".$card->cardtemplate->id_cardtemplate);
                        @endphp
                    <tr>
                        <th scope="row">1</th>
                        <td><a href="/{{ $card->url }}">{{ $card->cardtemplate->name }}</a></td>
                        <td>{{ $card->created_at }} (days to go: {{ $card->daysLeft() }})</td>
                        <td><a class="btn btn-success" href="/personal/edit/{{ $card->id_cardtemplate }}">Edit</a></td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@stop
