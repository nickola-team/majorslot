@extends('frontend.iris.layouts.app', ['logo' => 'luxury01'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.index')
@stop