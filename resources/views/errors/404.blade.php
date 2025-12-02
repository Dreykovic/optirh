@extends('errors.base')

@section('error-icon')
    <i class="fa fa-search fa-5x text-info"></i>
@endsection

@section('error-code', '404')

@section('error-title', 'Page Non Trouvée')

@section('error-message', 'La page que vous recherchez n\'existe pas ou a été déplacée.')
