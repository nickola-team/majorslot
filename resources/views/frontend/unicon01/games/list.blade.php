@extends('frontend.newheaven.layouts.app', ['logo' => 'unicon01'])
@section('page-title', $title)

@section('content')
@include('frontend.newheaven.games.list')
@stop