@extends('frontend.venus.layouts.app', ['logo' => 'africa'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop