@extends('frontend.iris.layouts.app', ['logo' => 'laslot'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.index')
@stop