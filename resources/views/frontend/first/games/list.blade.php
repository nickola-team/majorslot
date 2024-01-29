@extends('frontend.punch.layouts.app', ['logo' => 'first'])
@section('page-title', $title)

@section('content')
@include('frontend.punch.games.list')
@stop