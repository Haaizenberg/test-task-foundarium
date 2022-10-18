Запуск через <b>docker-commpose</b>:

1. <code>docker-compose build app</code>
2. Настроить .env
3. <code>docker-compose up</code>

Миграции! <code>php artisan migrate</code>

Запуск тестов:
<code>docker-compose exec app php artisan test</code>
ИЛИ
<code>php artisan test</code>

Файл swagger документации: swagger-docs.yaml
