@extends('errors.base')

@section('error-icon')
    <i class="fa fa-tools fa-5x text-secondary"></i>
@endsection

@section('error-code', '503')

@section('error-title', 'Maintenance en Cours')

@section('error-message', 'Le site est actuellement en maintenance. Nous serons de retour très bientôt.')
