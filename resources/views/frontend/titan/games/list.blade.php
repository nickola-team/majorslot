@extends('frontend.newheaven.layouts.app', ['logo' => 'titan'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop