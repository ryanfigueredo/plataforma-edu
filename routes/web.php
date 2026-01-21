<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvasaoAIController;
use App\Http\Controllers\LgpdController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/dados', [DashboardController::class, 'dadosDashboard'])->name('api.dashboard.dados');
    
    // LGPD
    Route::get('/lgpd/consentimento', [LgpdController::class, 'consentimento'])->name('lgpd.consentimento');
    Route::post('/lgpd/consentimento', [LgpdController::class, 'processarConsentimento']);
    Route::get('/lgpd/exportar', [LgpdController::class, 'exportarDados'])->name('lgpd.exportar');
    Route::get('/lgpd/auditoria', [LgpdController::class, 'auditoria'])->name('lgpd.auditoria');
    Route::post('/lgpd/exclusao', [LgpdController::class, 'solicitarExclusao'])->name('lgpd.exclusao');
    
    // Notificações
    Route::get('/notificacoes', [NotificationController::class, 'index'])->name('notificacoes.index');
    Route::post('/notificacoes/{id}/ler', [NotificationController::class, 'marcarComoLida'])->name('notificacoes.ler');
    Route::post('/notificacoes/ler-todas', [NotificationController::class, 'marcarTodasComoLidas'])->name('notificacoes.ler-todas');
    Route::get('/api/notificacoes/nao-lidas', [NotificationController::class, 'naoLidas'])->name('api.notificacoes.nao-lidas');
    
    // Evasão AI - Acesso restrito
    Route::middleware(['role:diretor,administrador'])->group(function () {
        Route::get('/evasao', [EvasaoAIController::class, 'index'])->name('evasao.index');
        Route::get('/evasao/{id}', [EvasaoAIController::class, 'show'])->name('evasao.show');
        Route::post('/evasao/{id}/acao', [EvasaoAIController::class, 'registrarAcao'])->name('evasao.acao');
        Route::post('/api/evasao/analisar-aluno/{id}', [EvasaoAIController::class, 'analisarAluno'])->name('api.evasao.analisar-aluno');
        Route::post('/api/evasao/analisar-todos', [EvasaoAIController::class, 'analisarTodos'])->name('api.evasao.analisar-todos');
        Route::post('/api/evasao/analisar-turma/{id}', [EvasaoAIController::class, 'analisarTurma'])->name('api.evasao.analisar-turma');
    });
    
    // Supervisor também pode ver alertas de suas turmas
    Route::middleware(['role:supervisor,diretor,administrador'])->group(function () {
        Route::get('/evasao', [EvasaoAIController::class, 'index'])->name('evasao.index');
        Route::get('/evasao/{id}', [EvasaoAIController::class, 'show'])->name('evasao.show');
    });
});
