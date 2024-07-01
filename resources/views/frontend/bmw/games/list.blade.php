@extends('frontend.newheaven.layouts.app', ['logo' => 'bmw'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop