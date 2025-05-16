@extends('frontend.newheaven.layouts.app', ['logo' => 'hwangjae'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop