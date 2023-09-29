@extends('frontend.venus.layouts.app', ['logo' => 'apple01'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop