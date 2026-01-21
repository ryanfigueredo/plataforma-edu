# Plataforma Educacional

Sistema de gestão acadêmica que desenvolvi em Laravel para ajudar instituições de ensino a gerenciar alunos, monitorar desempenho e, principalmente, prevenir a evasão escolar usando inteligência artificial.

## O que esse projeto faz?

Basicamente, é uma plataforma completa para gestão acadêmica com algumas funcionalidades que acho bem interessantes:

### 🎓 Gestão Acadêmica Completa
Sistema de controle de acesso por perfis (RBAC) onde cada tipo de usuário tem sua própria interface e permissões. Alunos veem só o que precisam, supervisores gerenciam suas turmas, diretores têm acesso a relatórios completos e administradores controlam tudo.

### 🤖 IA para Prevenção de Evasão
Essa é a parte que mais me empolga. Desenvolvi um algoritmo que analisa padrões de frequência, desempenho acadêmico e comportamento dos alunos para identificar quem está em risco de evasão. O sistema gera alertas automáticos para a diretoria quando detecta algo preocupante, permitindo intervenções precoces.

### 📊 Dashboard em Tempo Real
Criei um painel administrativo que mostra métricas importantes como taxa de retenção, alunos em risco, engajamento médio e saúde financeira da instituição. Tudo atualizado em tempo real para facilitar a tomada de decisão.

### 🔒 Conformidade LGPD
Como estamos lidando com dados sensíveis, implementei criptografia AES-256, logs de auditoria completos e sistema de consentimento. Também tem a funcionalidade de direito ao esquecimento, caso alguém solicite a exclusão dos dados.

### ♿ Acessibilidade
Fiz questão de tornar o sistema acessível, seguindo as diretrizes WCAG 2.1 AA. Tem suporte a leitores de tela, navegação por teclado, alto contraste e toda a estrutura semântica necessária.

### 🔔 Sistema de Notificações
Implementei um sistema centralizado de notificações para comunicação entre a instituição, alunos e professores. Alertas, avisos e comunicados ficam todos organizados.

## Tecnologias que usei

- **Laravel 10** - Framework PHP que escolhi pela robustez e facilidade de desenvolvimento
- **MySQL** - Banco de dados relacional
- **Spatie Laravel Permission** - Para gerenciar roles e permissões
- **Laravel Sanctum** - Autenticação de API
- **PHP 8.2+** - Versão mais recente do PHP com melhor performance

## Como rodar o projeto

### Pré-requisitos
Você vai precisar de:
- PHP 8.1 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js 18+ (se for compilar assets, mas não é obrigatório)

### Instalação

```bash
# Clone o repositório
git clone <repository-url>
cd plataforma-educacional

# Instale as dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco de dados no .env
# DB_DATABASE=plataforma_educacional
# DB_USERNAME=seu_usuario
# DB_PASSWORD=sua_senha

# Crie o banco de dados
mysql -u root -p -e "CREATE DATABASE plataforma_educacional;"

# Execute as migrações e seeders (isso cria os dados de teste)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate --seed

# Inicie o servidor
php artisan serve
```

Acesse **http://localhost:8000** e pronto!

## Credenciais para testar

Criei alguns usuários de teste para facilitar a demonstração. Use essas credenciais para acessar o sistema:

### 🔑 Credenciais de Acesso

**ADMINISTRADOR (Acesso Total)**
- Email: `admin@plataforma.local`
- Senha: `password`

**DIRETOR**
- Email: `diretor@plataforma.local`
- Senha: `password`

**SUPERVISOR**
- Email: `supervisor@plataforma.local`
- Senha: `password`

**ALUNO**
- Email: `aluno@plataforma.local`
- Senha: `password`

Esses usuários são criados automaticamente quando você executa `php artisan db:seed`.

## Como funciona a IA de evasão

Desenvolvi um algoritmo que calcula um score de risco (de 0 a 1) baseado em vários fatores:

- **Frequência** (peso 35%): Analisa padrões de presença e ausência
- **Desempenho** (peso 30%): Avalia notas e performance acadêmica
- **Tempo** (peso 20%): Considera quanto tempo o aluno está na instituição
- **Histórico** (peso 15%): Leva em conta alertas anteriores

Quando o score ultrapassa o threshold configurado (padrão 0.7), o sistema gera um alerta automático para a diretoria. Também tem um job agendado que roda diariamente às 2h da manhã para analisar todos os alunos.

## Estrutura do projeto

Organizei o código seguindo as melhores práticas do Laravel:

```
app/
├── Http/
│   ├── Controllers/     # Controllers organizados por funcionalidade
│   ├── Middleware/      # CheckRole, LgpdAudit, etc
│   └── Requests/        # Form Requests para validação
├── Models/              # Eloquent models
├── Services/            # Lógica de negócio (EvasaoAIService, LgpdService)
└── Jobs/                # Jobs para processar em background

database/
├── migrations/          # Estrutura do banco
└── seeders/            # Dados iniciais para testes

resources/
└── views/               # Views Blade organizadas por módulo

tests/                   # Testes automatizados
```

## Testes

Implementei testes para as funcionalidades principais:

```bash
# Rodar todos os testes
php artisan test

# Testes específicos
php artisan test --filter EvasaoAITest
php artisan test --filter LgpdTest
php artisan test --filter RbacTest
```

## Deploy

O projeto está configurado para deploy em várias plataformas. Tem `Procfile` para Heroku/Railway, `railway.json` para Railway e `render.yaml` para Render. Também tem Dockerfile caso prefira containerizar.

## O que aprendi desenvolvendo isso

Foi um projeto desafiador que me permitiu trabalhar com:
- Arquitetura de software escalável
- Machine Learning aplicado a problemas reais
- Conformidade com LGPD
- Acessibilidade web
- Testes automatizados
- Deploy em produção

## Contato

Desenvolvido por **Ryan Figueredo**

Se tiver alguma dúvida ou sugestão, fique à vontade para entrar em contato!
