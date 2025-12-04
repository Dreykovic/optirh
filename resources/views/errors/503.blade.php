@extends('errors.base')

@section('error-icon')
    <i class="icofont-tools-alt-2 icofont-5x text-secondary"></i>
@endsection

@section('error-code', '503')

@section('error-title', 'Maintenance en Cours')

@section('error-message', 'Le site est actuellement en maintenance. Nous serons de retour très bientôt.')
