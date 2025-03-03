type ReportProps = {
    start: string,
    end: string,
    label: string,
}

type allReportsProps = {
    current_month: ReportProps,
    current_week: ReportProps,
    last_8_days: ReportProps,
    previous_month: ReportProps,
    previous_week: ReportProps,
}
type ReportTableProps = {
    daily: {
        count_total: string,
        visit_date: string,
        count_not_confirmed: string,
        count_confirmed: string,
    }[],
    domains: {
        count_total: string,
        domain: string,
        count_not_confirmed: string,
        count_confirmed: string,
    }[],
    metrics: {
        avg_daily_visits: string,
        conversion_rate: string,
        days_count: string
    },
    period: ReportProps,
    total: {
        count_confirmed: string,
        count_not_confirmed: string,
        count_total: string
    }
}

type ReportTable = {
    current_month: ReportTableProps,
    current_week: ReportTableProps,
    last_8_days: ReportTableProps,
    previous_month: ReportTableProps,
    previous_week: ReportTableProps,
}

type TodayVsYesterdayProps = {
    currentTime: string,
    difference: {
        confirmed: number,
        confirmed_percent: number,
        percent: number,
        total: number
    },
    isPositive: boolean,
    today: {
        confirmed: number,
        not_confirmed: number,
        total: number
    },
    yesterday: {
        confirmed: number,
        not_confirmed: number,
        total: number
    }
}

type DashboardProps = {
    allReports: ReportTable,
    reports: allReportsProps,
    todayVsYesterday: TodayVsYesterdayProps,
    totalStats: {
        total_confirmed: number,
        total_not_confirmed: number,
        total_visits: number
    }
}
