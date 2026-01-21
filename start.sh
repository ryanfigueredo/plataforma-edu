#!/bin/bash

# Script de inicialização para Railway
# Executa migrações e configura tudo automaticamente

set -e

echo "🚀 Iniciando aplicação..."

# Limpar cache
rm -rf bootstrap/cache/*.php

# Configurar permissões
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# Executar migrações (se necessário)
echo "📊 Verificando migrações..."
php artisan migrate --force || echo "⚠️  Migrações já executadas ou erro (pode ignorar se já rodou)"

# Cachear configurações
echo "⚙️  Cacheando configurações..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Iniciar servidor
echo "✅ Iniciando servidor..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
