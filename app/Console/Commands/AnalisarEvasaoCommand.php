<?php

namespace App\Console\Commands;

use App\Services\EvasaoAIService;
use Illuminate\Console\Command;

class AnalisarEvasaoCommand extends Command
{
    protected $signature = 'evasao:analisar-todos';
    protected $description = 'Analisa risco de evasão de todos os alunos ativos';

    public function handle(EvasaoAIService $service): int
    {
        $this->info('Iniciando análise de evasão...');
        
        $service->analisarTodosAlunos();
        
        $this->info('Análise concluída com sucesso!');
        
        return Command::SUCCESS;
    }
}
