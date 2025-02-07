@extends('frontend.iris.layouts.app', ['logo' => 'best'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop