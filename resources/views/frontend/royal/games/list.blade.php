@extends('frontend.venus.layouts.app', ['logo' => 'royal'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop