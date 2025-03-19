@extends('frontend.newheaven.layouts.app', ['logo' => 'pink'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop