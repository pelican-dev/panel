<?php

return [
    'title' => 'Agendamentos',
    'new' => 'Novo agendamento',
    'edit' => 'Editar agendamento',
    'save' => 'Salvar agendamento',
    'delete' => 'Excluir agendamento',
    'import' => 'Importar agendamento',
    'export' => 'Exportar agendamento',
    'name' => 'Nome',
    'cron' => 'Cron',
    'status' => 'Status',
    'schedule_status' => [
        'inactive' => 'Inativo',
        'processing' => 'Processando',
        'active' => 'Ativo',
    ],
    'no_tasks' => 'Sem tarefas',
    'run_now' => 'Executar agora',
    'online_only' => 'Somente quando estiver on-line',
    'last_run' => 'Última execução',
    'next_run' => 'Próxima execução',
    'never' => 'Nunca',
    'cancel' => 'Cancelar',

    'only_online' => 'Somente quando o servidor estiver on-line?',
    'only_online_hint' => 'Só executa essa agenda quando o servidor está em estado de execução.',
    'enabled' => 'Habilitar agendamento?',
    'enabled_hint' => 'Este agendamento será executado automaticamente se ativado.',

    'cron_body' => 'Tenha em mente que as entradas do cron abaixo sempre assumem UTC.',
    'cron_timezone' => 'Próxima execução em seu fuso horário (:timezone): <b> :next_run </b>',

    'invalid' => 'Inválido',

    'time' => [
        'minute' => 'Minuto',
        'hour' => 'Hora',
        'day' => 'Dia',
        'week' => 'Semana',
        'month' => 'Mês',
        'day_of_month' => 'Dia do mês',
        'day_of_week' => 'Dia da Semana',

        'hourly' => 'De hora em hora',
        'daily' => 'Diariamente',
        'weekly_mon' => 'Semanalmente (Segunda-feira)',
        'weekly_sun' => 'Semanalmente (Domingo)',
        'monthly' => 'Mensalmente',
        'every_min' => 'A cada X minutos',
        'every_hour' => 'A cada X horas',
        'every_day' => 'A cada X dias',
        'every_week' => 'A cada X semanas',
        'every_month' => 'A cada X meses',
        'every_day_of_week' => 'Todo dia X da semana',

        'every' => 'Todo',
        'minutes' => 'Minutos',
        'hours' => 'Horas',
        'days' => 'Dias',
        'months' => 'Meses',

        'monday' => 'Segunda-feira',
        'tuesday' => 'Terça-feira',
        'wednesday' => 'Quarta-feira',
        'thursday' => 'Quinta-feira',
        'friday' => 'Sexta-feira',
        'saturday' => 'Sábado',
        'sunday' => 'Domingo',
    ],

    'tasks' => [
        'title' => 'Tarefas',
        'create' => 'Criar Tarefa',
        'limit' => 'Limite de Tarefas Alcançado',
        'action' => 'Ação',
        'payload' => 'Carga útil',
        'no_payload' => 'No Payload',
        'time_offset' => 'Diferença horária',
        'first_task' => 'Primeira tarefa',
        'seconds' => 'Segundos',
        'continue_on_failure' => 'Continuar ao falhar',

        'actions' => [
            'title' => 'Ação',
            'power' => [
                'title' => 'Enviar ação de energia',
                'action' => 'Ação de energia',
                'start' => 'Iniciar',
                'stop' => 'Parar',
                'restart' => 'Reiniciar',
                'kill' => 'Forçar Parada',
            ],
            'command' => [
                'title' => 'Enviar comando',
                'command' => 'Comando',
            ],
            'backup' => [
                'title' => 'Criar Backup',
                'files_to_ignore' => 'Arquivos para ignorar',
            ],
            'delete_files' => [
                'title' => 'Delete Files',
                'files_to_delete' => 'Files to Delete',
            ],
        ],
    ],

    'notification_invalid_cron' => 'Os dados cron fornecidos não correspondem a uma expressão válida.',

    'import_action' => [
        'file' => 'Arquivo',
        'url' => 'URL',
        'schedule_help' => 'Este deve ser um arquivo JSON bruto (schedule-daily-restart.json)',
        'url_help' => 'URLs devem apontar diretamente para o arquivo .json bruto',
        'add_url' => 'Nova URL',
        'import_failed' => 'Importação falhou',
        'import_success' => 'Importação bem-sucedida',
    ],
];
