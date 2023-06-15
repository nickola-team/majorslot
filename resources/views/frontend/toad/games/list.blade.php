@extends('frontend.cinema.layouts.app', ['logo' => 'toad'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop