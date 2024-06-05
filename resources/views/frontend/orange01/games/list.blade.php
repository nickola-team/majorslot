@extends('frontend.iris.layouts.app', ['logo' => 'orange01'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop