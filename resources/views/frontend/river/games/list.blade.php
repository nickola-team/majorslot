@extends('frontend.venus.layouts.app', ['logo' => 'river'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop