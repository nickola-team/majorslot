@extends('frontend.sealwort.layouts.app', ['logo' => 'sealwort'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop