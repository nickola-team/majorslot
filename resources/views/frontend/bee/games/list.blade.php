@extends('frontend.venus.layouts.app', ['logo' => 'bee'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop