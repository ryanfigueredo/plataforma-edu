<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do Módulo de IA para Evasão
    |--------------------------------------------------------------------------
    */

    'evasao_threshold' => env('AI_EVASION_THRESHOLD', 0.7),

    'analysis_interval' => env('AI_ANALYSIS_INTERVAL', 24), // horas

    'pesos' => [
        'frequencia' => 0.35,
        'desempenho' => 0.30,
        'tempo' => 0.20,
        'historico' => 0.15,
    ],
];
