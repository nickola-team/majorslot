@extends('frontend.venus.layouts.app', ['logo' => 'gn'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop