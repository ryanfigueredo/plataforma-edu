<?php

namespace App\Jobs;

use App\Models\Aluno;
use App\Services\EvasaoAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalisarEvasaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Aluno $aluno;

    /**
     * Create a new job instance.
     */
    public function __construct(Aluno $aluno)
    {
        $this->aluno = $aluno;
    }

    /**
     * Execute the job.
     */
    public function handle(EvasaoAIService $service): void
    {
        try {
            $service->analisarRiscoEvasao($this->aluno);
        } catch (\Exception $e) {
            Log::error('Erro ao analisar evasão do aluno', [
                'aluno_id' => $this->aluno->id,
                'erro' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}
