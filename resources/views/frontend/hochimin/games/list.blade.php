@extends('frontend.venus.layouts.app', ['logo' => 'hochimin'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop