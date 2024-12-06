@extends('frontend.natural.layouts.app', ['logo' => 'stone01'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop