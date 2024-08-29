@extends('frontend.venus.layouts.app', ['logo' => 'cherry'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop