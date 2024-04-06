@extends('frontend.iris.layouts.app', ['logo' => 'grand01'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop