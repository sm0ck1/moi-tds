<?php

namespace App\Http\Controllers;

use App\Models\VisitUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Получаем текущую дату и время
        $now = Carbon::now();

        // Получаем данные "сегодня до текущего времени" и "вчера за тот же период"
        $todayStats = $this->getTodayVsYesterdayStats();

        // Готовим данные для всех основных отчетов
        $reports = [
            'last_8_days' => [
                'start' => $now->copy()->subDays(7)->startOfDay()->format('Y-m-d'),
                'end' => $now->format('Y-m-d'),
                'label' => 'Последние 8 дней'
            ],
            'current_week' => [
                'start' => $now->copy()->startOfWeek()->format('Y-m-d'),
                'end' => $now->copy()->endOfWeek()->format('Y-m-d'),
                'label' => 'Текущая неделя'
            ],
            'previous_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek()->format('Y-m-d'),
                'end' => $now->copy()->subWeek()->endOfWeek()->format('Y-m-d'),
                'label' => 'Предыдущая неделя'
            ],
            'current_month' => [
                'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
                'end' => $now->copy()->endOfMonth()->format('Y-m-d'),
                'label' => 'Текущий месяц'
            ],
            'previous_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                'end' => $now->copy()->subMonth()->endOfMonth()->format('Y-m-d'),
                'label' => 'Предыдущий месяц'
            ]
        ];

        // Пользовательский диапазон дат, если указан
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        if ($customStart && $customEnd) {
            $reports['custom'] = [
                'start' => $customStart,
                'end' => $customEnd,
                'label' => 'Пользовательский период'
            ];
        }

        // Собираем данные для всех отчетов
        $dashboardData = [];

        foreach ($reports as $key => $range) {
            $dashboardData[$key] = $this->getDataForPeriod($range['start'], $range['end']);
            $dashboardData[$key]['period'] = $range;
        }

        // Базовая статистика за все время (для общих показателей)
        $totalStats = VisitUser::query()
            ->selectRaw('
                COUNT(*) as total_visits,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as total_confirmed,
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as total_not_confirmed')
            ->first();

        return Inertia::render('Dashboard', [
            'allReports' => $dashboardData,
            'totalStats' => $totalStats,
            'reports' => $reports,
            'hasCustomPeriod' => isset($reports['custom']),
            'todayVsYesterday' => $todayStats
        ]);
    }

    /**
     * Получить статистику "сегодня" в сравнении с "вчера" на то же время
     *
     * @return array
     */
    private function getTodayVsYesterdayStats()
    {
        $now = Carbon::now();

        // Получаем текущие часы и минуты
        $currentTime = $now->format('H:i:s');

        // Сегодня до текущего времени
        $todayStart = $now->copy()->startOfDay()->format('Y-m-d');
        $todayEnd = $now->format('Y-m-d H:i:s');

        // Вчера до этого же времени
        $yesterdayStart = $now->copy()->subDay()->startOfDay()->format('Y-m-d');
        $yesterdayEnd = $now->copy()->subDay()->format('Y-m-d') . ' ' . $currentTime;

        // Статистика за сегодня до текущего времени
        $todayStats = VisitUser::query()
            ->selectRaw('
                COUNT(*) as count_total,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as count_confirmed,
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as count_not_confirmed')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->first();

        // Статистика за вчера до этого же времени
        $yesterdayStats = VisitUser::query()
            ->selectRaw('
                COUNT(*) as count_total,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as count_confirmed,
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as count_not_confirmed')
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->first();

        // Расчет разницы и процентного изменения
        $difference = $todayStats->count_total - $yesterdayStats->count_total;
        $percentChange = 0;

        if ($yesterdayStats->count_total > 0) {
            $percentChange = round(($difference / $yesterdayStats->count_total) * 100, 2);
        }

        // Разница для подтвержденных
        $confirmedDifference = $todayStats->count_confirmed - $yesterdayStats->count_confirmed;
        $confirmedPercentChange = 0;

        if ($yesterdayStats->count_confirmed > 0) {
            $confirmedPercentChange = round(($confirmedDifference / $yesterdayStats->count_confirmed) * 100, 2);
        }

        return [
            'today' => [
                'total' => $todayStats->count_total,
                'confirmed' => $todayStats->count_confirmed,
                'not_confirmed' => $todayStats->count_not_confirmed
            ],
            'yesterday' => [
                'total' => $yesterdayStats->count_total,
                'confirmed' => $yesterdayStats->count_confirmed,
                'not_confirmed' => $yesterdayStats->count_not_confirmed
            ],
            'difference' => [
                'total' => $difference,
                'percent' => $percentChange,
                'confirmed' => $confirmedDifference,
                'confirmed_percent' => $confirmedPercentChange
            ],
            'currentTime' => $now->format('H:i'),
            'isPositive' => $difference >= 0
        ];
    }

    /**
     * Получить все данные за указанный период
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getDataForPeriod($startDate, $endDate)
    {
        // Суммарная статистика
        $totalStats = VisitUser::query()
            ->selectRaw('
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as count_not_confirmed,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as count_confirmed,
                COUNT(*) as count_total')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->first();

        // Статистика по дням
        $dailyStats = VisitUser::query()
            ->selectRaw('visit_date,
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as count_not_confirmed,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as count_confirmed,
                COUNT(*) as count_total')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy('visit_date')
            ->orderBy('visit_date', 'ASC')
            ->get();

        // Статистика по доменам
        $domainStats = VisitUser::query()
            ->selectRaw('
                SUBSTRING_INDEX(SUBSTRING_INDEX(referrer, "/", 1), "https://", -1) as domain,
                SUM(CASE WHEN confirm_click = 0 THEN 1 ELSE 0 END) as count_not_confirmed,
                SUM(CASE WHEN confirm_click = 1 THEN 1 ELSE 0 END) as count_confirmed,
                COUNT(*) as count_total')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy('domain')
            ->orderBy('count_total', 'DESC')
            ->limit(10) // Top 10 доменов
            ->get();

        // Расчет коэффициента конверсии
        $conversionRate = 0;
        if ($totalStats->count_total > 0) {
            $conversionRate = ($totalStats->count_confirmed / $totalStats->count_total) * 100;
        }

        return [
            'total' => $totalStats,
            'daily' => $dailyStats,
            'domains' => $domainStats,
            'metrics' => [
                'conversion_rate' => round($conversionRate, 2),
                'days_count' => $dailyStats->count(),
                'avg_daily_visits' => $dailyStats->count() > 0 ? round($totalStats->count_total / $dailyStats->count(), 2) : 0
            ]
        ];
    }
}
