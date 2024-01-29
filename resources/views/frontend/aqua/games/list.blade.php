@extends('frontend.cinema.layouts.app', ['logo' => 'aqua'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop