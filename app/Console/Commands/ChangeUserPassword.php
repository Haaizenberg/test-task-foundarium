<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Меняет пароль у заданного по email польpователя';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Введите email пользователя которому желаете сменить пароль:');
        $password = $this->ask("Введите новый пароль для пользователя с адресом \"$email\":");

        if (User::changePassword($email, $password)) {
            $this->info('Пароль успешно изменен!');
        } else {
            $this->error('Ошибка при смене пароля! Смотрите log для подробностей ошибки.');
        }
    }
}
