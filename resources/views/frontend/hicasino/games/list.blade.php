@extends('frontend.newheaven.layouts.app', ['logo' => 'hicasino'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop