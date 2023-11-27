@extends('frontend.venus.layouts.app', ['logo' => 'daiso'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop