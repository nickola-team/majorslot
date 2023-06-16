@extends('frontend.cinema.layouts.app', ['logo' => 'norae'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop