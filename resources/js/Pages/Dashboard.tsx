import * as React from 'react';
import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PageProps} from "@/types";
import ReportTable from "@/Pages/Reports/Partials/ShowDaily";
import Box from "@mui/material/Box";
import TodayVsYesterdayCard from "@/Pages/Reports/Partials/TodayVsYesterdayCard";
import {allReportsProps, DashboardProps} from "@/types/report";

interface TabPanelProps {
    children?: React.ReactNode;
    index: number;
    value: number;
}

function CustomTabPanel(props: TabPanelProps) {
    const {children, value, index, ...other} = props;

    return (
        <div
            role="tabpanel"
            hidden={value !== index}
            id={`simple-tabpanel-${index}`}
            aria-labelledby={`simple-tab-${index}`}
            {...other}
        >
            {value === index && <Box>{children}</Box>}
        </div>
    );
}

export default function Dashboard({allReports, reports, todayVsYesterday}: PageProps<DashboardProps>) {
    const [value, setValue] = React.useState(0);

    return (
        <DashboardLayout
            header={{
                title: 'Dashboard',
                filters:  [
                    {
                        type: 'button' as const,
                        href: `/dashboard#filter=today`,
                        label: 'Today vs Yesterday',
                        current: value === -1,
                        handleClick: () => {
                            setValue(-1)
                        }
                    },
                    ...Object.keys(reports).map((key, index) => {
                        const typedKey = key as keyof allReportsProps;
                        return {
                            type: 'button' as const,
                            href: `/dashboard#filter=${key}`,
                            label: reports[typedKey].label,
                            current: value === index,
                            handleClick: () => {
                                setValue(index)
                            }
                        }
                    })
                ],
            }}
        >

            <Box sx={{
                mt: 3
            }}>
                <CustomTabPanel key={-1} index={-1} value={value}>
                    <TodayVsYesterdayCard data={todayVsYesterday}/>
                </CustomTabPanel>
                {Object.keys(reports).map(
                    (key, index) => {
                        const typedKey = key as keyof allReportsProps;
                        return (
                            <CustomTabPanel key={key} index={index} value={value}>
                                <ReportTable report={allReports[typedKey]} />
                            </CustomTabPanel>
                        )
                    }
                )}
            </Box>
        </DashboardLayout>
    );
}
