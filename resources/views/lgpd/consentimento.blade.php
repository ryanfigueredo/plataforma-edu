@extends('layouts.app')

@section('title', 'Consentimento LGPD')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Termo de Consentimento - LGPD</h4>
            </div>
            <div class="card-body">
                <p>Para continuar utilizando a plataforma, é necessário que você concorde com o tratamento dos seus dados pessoais conforme a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>
                
                <h5>Dados Coletados</h5>
                <ul>
                    <li>Nome completo</li>
                    <li>E-mail</li>
                    <li>CPF</li>
                    <li>Data de nascimento</li>
                    <li>Telefone</li>
                    <li>Dados acadêmicos (frequência, desempenho, etc.)</li>
                </ul>
                
                <h5>Finalidade do Tratamento</h5>
                <p>Os dados são utilizados exclusivamente para:</p>
                <ul>
                    <li>Gestão acadêmica e administrativa</li>
                    <li>Análise de frequência e desempenho</li>
                    <li>Prevenção de evasão escolar</li>
                    <li>Comunicação institucional</li>
                </ul>
                
                <h5>Seus Direitos</h5>
                <p>Você tem direito a:</p>
                <ul>
                    <li>Acessar seus dados</li>
                    <li>Corrigir dados incompletos ou desatualizados</li>
                    <li>Solicitar exclusão dos dados</li>
                    <li>Exportar seus dados</li>
                    <li>Revogar o consentimento a qualquer momento</li>
                </ul>
                
                <form method="POST" action="{{ route('lgpd.consentimento') }}">
                    @csrf
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="consentimento" 
                               name="consentimento" 
                               value="1" 
                               required>
                        <label class="form-check-label" for="consentimento">
                            <strong>Concordo com o tratamento dos meus dados pessoais conforme descrito acima.</strong>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Confirmar e Continuar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
