<?php

namespace Database\Seeders;

use App\Models\PortalPlacement;
use App\Models\VisitUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitUserSeeder extends Seeder
{
    public function run()
    {
        $totalRecords = rand(50000, 150000);
        $chunkSize = 10000;
        $tempFile = storage_path('app/temp_visits.csv');

        // Получаем существующие ID порталов
        $portalPlacementIds = PortalPlacement::pluck('id')->toArray();

        // Создаем CSV файл
        $this->command->info('Generating CSV file...');
        $progressBar = $this->command->getOutput()->createProgressBar($totalRecords);

        $handle = fopen($tempFile, 'w');

        // Записываем данные чанками для контроля памяти
        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $records = [];
            $currentChunkSize = min($chunkSize, $totalRecords - $i);

            for ($j = 0; $j < $currentChunkSize; $j++) {
                $now = now();
                $record = [
                    mt_rand(0, 1) ? fake()->ipv6 : fake()->ipv4,
                    fake()->userAgent,
                    fake()->url,
                    fake()->dateTimeThisMonth->format('Y-m-d'),
                    $portalPlacementIds[array_rand($portalPlacementIds)],
                    $now,
                    $now,
                ];

                // Экранируем специальные символы
                $record = array_map(function ($field) {
                    return str_replace(["\n", "\r", "\t", ','], ' ', $field);
                }, $record);

                fputcsv($handle, $record);
                $progressBar->advance();
            }
        }

        fclose($handle);
        $progressBar->finish();

        $this->command->info("\nLoading data into MySQL...");

        // Отключаем внешние ключи и события
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VisitUser::unsetEventDispatcher();

        // Загружаем данные через LOAD DATA INFILE
        $query = "LOAD DATA LOCAL INFILE '".str_replace('\\', '/', $tempFile)."'
            INTO TABLE visit_users
            FIELDS TERMINATED BY ','
            OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            (ip_address, user_agent, referrer, visit_time, portal_placement_id, created_at, updated_at)";

        try {
            DB::statement($query);
            $this->command->info('Data loaded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error loading data: '.$e->getMessage());

            // Альтернативный вариант через INSERT если LOAD DATA INFILE недоступен
            $this->command->info('Falling back to INSERT...');

            $handle = fopen($tempFile, 'r');
            while (! feof($handle)) {
                $records = [];
                for ($i = 0; $i < 1000 && ! feof($handle); $i++) {
                    $record = fgetcsv($handle);
                    if ($record) {
                        $records[] = [
                            'ip_address' => $record[0],
                            'user_agent' => $record[1],
                            'referrer' => $record[2],
                            'visit_date' => $record[3],
                            'portal_placement_id' => $record[4],
                            'created_at' => $record[5],
                            'updated_at' => $record[6],
                        ];
                    }
                }
                if (! empty($records)) {
                    VisitUser::insert($records);
                }
            }
            fclose($handle);
        }

        // Включаем обратно внешние ключи
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Удаляем временный файл
        @unlink($tempFile);
    }
}
