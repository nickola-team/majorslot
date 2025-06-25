@extends('frontend.newheaven.layouts.app', ['logo' => 'star03'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop