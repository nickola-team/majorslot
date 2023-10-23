@extends('frontend.iris.layouts.app', ['logo' => 'sevenseven'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')

@stop
