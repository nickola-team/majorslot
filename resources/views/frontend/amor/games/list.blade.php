@extends('frontend.venus.layouts.app', ['logo' => 'amor'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop