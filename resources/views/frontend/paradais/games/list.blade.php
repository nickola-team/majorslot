@extends('frontend.natural.layouts.app', ['logo' => 'paradais'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop