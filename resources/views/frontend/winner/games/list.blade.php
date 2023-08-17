@extends('frontend.venus.layouts.app', ['logo' => 'winner'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop