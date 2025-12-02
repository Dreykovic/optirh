@extends('errors.base')

@section('error-icon')
    <i class="fa fa-exclamation-triangle fa-5x text-danger"></i>
@endsection

@section('error-code', '500')

@section('error-title', 'Erreur Serveur')

@section('error-message', 'Une erreur inattendue s\'est produite. Veuillez réessayer ultérieurement.')
