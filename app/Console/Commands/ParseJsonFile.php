<?php

namespace App\Console\Commands;

use App\Models\Email;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ParseJsonFile extends Command
{
    const FILENAME = '/public/test.json';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Разбирает JSON и вставляет данные если записи новые, 
                            удаляет записи из БД если таковых не существует в файле и 
                            обновляет, если данные были изменены в исходном файле';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Читаем json
        if (! Storage::exists(self::FILENAME)) {
            return $this->error('Файла "' . self::FILENAME .  '" не существует!');
        }
        $file = json_decode(Storage::get(self::FILENAME), true);

        // Собираем id записей и обновляем\создаем записи
        $ids = [];
        foreach ($file['data'] as $item) {
            $ids[] = $item['id'];
            Email::updateOrCreateFrom($item);
        }

        // Удаляем только те записи с базы
        // которые не содержатся в json-е 
        Email::deleteByIdNotIn($ids);
        
        $this->info('Завершено!');
    }
}
