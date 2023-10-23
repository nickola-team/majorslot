@extends('frontend.iris.layouts.app', ['logo' => 'nyx'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')

@stop
