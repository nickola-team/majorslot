@extends('frontend.venus.layouts.app', ['logo' => '88'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop