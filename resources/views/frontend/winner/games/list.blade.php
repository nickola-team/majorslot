@extends('frontend.natural.layouts.app', ['logo' => 'winner'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop