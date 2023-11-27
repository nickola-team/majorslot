@extends('frontend.venus.layouts.app', ['logo' => 'yokjon'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop