@extends('frontend.venus.layouts.app', ['logo' => 'arirang'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop