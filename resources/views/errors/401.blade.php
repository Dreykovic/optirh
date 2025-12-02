@extends('errors.base')

@section('error-icon')
    <i class="fa fa-lock fa-5x text-warning"></i>
@endsection

@section('error-code', '401')

@section('error-title', 'Non Authentifié')

@section('error-message', 'Veuillez vous connecter pour accéder à cette page.')

@section('error-action')
    <a href="{{ route('login') }}" class="btn btn-outline-light">
        <i class="fa fa-sign-in-alt me-2"></i>Se connecter
    </a>
@endsection
