import React from 'react';
import {
    Box,
    Card,
    CardContent,
    Typography,
    Divider,
    Chip,
    Stack,
    Paper,
    LinearProgress
} from '@mui/material';

import TrendingUpIcon from '@mui/icons-material/TrendingUp';
import TrendingDownIcon from '@mui/icons-material/TrendingDown';
import AccessTimeIcon from '@mui/icons-material/AccessTime';
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import CancelIcon from '@mui/icons-material/Cancel';
import {TodayVsYesterdayProps} from "@/types/report";
import Grid from "@mui/material/Grid2";

const TodayVsYesterdayCard = ({data}: { data: TodayVsYesterdayProps }) => {
    const {
        currentTime,
        difference,
        isPositive,
        today,
        yesterday
    } = data;

    // Calculate confirmation percentages
    const todayConfirmationPercent = (today.confirmed / today.total) * 100;
    const yesterdayConfirmationPercent = (yesterday.confirmed / yesterday.total) * 100;

    return (
        <Card elevation={3}>
            <CardContent>
                <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
                    <Typography variant="h5" fontWeight="bold">
                        Сравнение показателей
                    </Typography>
                    <Box display="flex" alignItems="center">
                        <AccessTimeIcon fontSize="small" color="action" sx={{mr: 0.5}}/>
                        <Typography variant="body2" color="text.secondary">
                            {currentTime}
                        </Typography>
                    </Box>
                </Box>

                {/* Summary Chip */}
                <Box mb={3} display="flex" justifyContent="center">
                    <Chip
                        icon={isPositive ? <TrendingUpIcon/> : <TrendingDownIcon/>}
                        label={`${isPositive ? '+' : ''}${difference.percent.toFixed(1)}% относительно вчера`}
                        color={isPositive ? "success" : "error"}
                        variant="filled"
                        size="medium"
                    />
                </Box>

                {/* Comparison Cards */}
                <Grid container spacing={3} mb={3}>
                    <Grid size={{
                        xs: 12,
                        sm: 6
                    }}>
                        <Paper elevation={2}
                               sx={{p: 2, height: '100%', bgcolor: 'primary.light', color: 'primary.contrastText'}}>
                            <Typography variant="h6" gutterBottom align="center">
                                Сегодня
                            </Typography>
                            <Typography variant="h4" align="center" fontWeight="bold">
                                {today.total}
                            </Typography>
                            <Box mt={1}>
                                <Stack direction="row" justifyContent="space-between" mb={0.5}>
                                    <Typography variant="body2">Подтверждено:</Typography>
                                    <Typography variant="body2" fontWeight="medium">{today.confirmed}</Typography>
                                </Stack>
                                <Stack direction="row" justifyContent="space-between">
                                    <Typography variant="body2">Не подтверждено:</Typography>
                                    <Typography variant="body2" fontWeight="medium">{today.not_confirmed}</Typography>
                                </Stack>
                                <LinearProgress
                                    variant="determinate"
                                    value={todayConfirmationPercent}
                                    sx={{mt: 1, height: 8, borderRadius: 1}}
                                />
                                <Typography variant="caption" align="right" display="block" sx={{mt: 0.5}}>
                                    {todayConfirmationPercent.toFixed(1)}% подтверждено
                                </Typography>
                            </Box>
                        </Paper>
                    </Grid>

                    <Grid size={{
                        xs: 12,
                        sm: 6
                    }}>
                        <Paper elevation={2} sx={{p: 2, height: '100%', bgcolor: 'grey.200'}}>
                            <Typography variant="h6" gutterBottom align="center" color="text.secondary">
                                Вчера
                            </Typography>
                            <Typography variant="h4" align="center" fontWeight="bold" color="text.primary">
                                {yesterday.total}
                            </Typography>
                            <Box mt={1}>
                                <Stack direction="row" justifyContent="space-between" mb={0.5}>
                                    <Typography variant="body2" color="text.secondary">Подтверждено:</Typography>
                                    <Typography variant="body2" fontWeight="medium">{yesterday.confirmed}</Typography>
                                </Stack>
                                <Stack direction="row" justifyContent="space-between">
                                    <Typography variant="body2" color="text.secondary">Не подтверждено:</Typography>
                                    <Typography variant="body2"
                                                fontWeight="medium">{yesterday.not_confirmed}</Typography>
                                </Stack>
                                <LinearProgress
                                    variant="determinate"
                                    value={yesterdayConfirmationPercent}
                                    sx={{mt: 1, height: 8, borderRadius: 1, bgcolor: 'grey.300'}}
                                />
                                <Typography variant="caption" align="right" display="block" sx={{mt: 0.5}}>
                                    {yesterdayConfirmationPercent.toFixed(1)}% подтверждено
                                </Typography>
                            </Box>
                        </Paper>
                    </Grid>
                </Grid>

                {/* Detailed stats */}
                <Divider sx={{my: 2}}/>

                <Typography variant="subtitle1" gutterBottom fontWeight="medium">
                    Детальное сравнение:
                </Typography>

                <Grid container spacing={2}>
                    <Grid size={{
                        xs: 12,
                        sm: 6
                    }}>
                        <Box display="flex" justifyContent="space-between" alignItems="center">
                            <Box display="flex" alignItems="center">
                                <CheckCircleIcon color="success" sx={{mr: 1}}/>
                                <Typography>Подтвержденные</Typography>
                            </Box>
                            <Box display="flex" alignItems="center">
                                <Typography variant="body1" fontWeight="medium" mr={1}>
                                    {isPositive ? '+' : ''}{difference.confirmed}
                                </Typography>
                                <Chip
                                    size="small"
                                    label={`${isPositive ? '+' : ''}${difference.confirmed_percent.toFixed(1)}%`}
                                    color={difference.confirmed_percent > 0 ? "success" : "error"}
                                    variant="outlined"
                                />
                            </Box>
                        </Box>
                    </Grid>

                    <Grid size={{
                        xs: 12,
                        sm: 6
                    }}>
                        <Box display="flex" justifyContent="space-between" alignItems="center">
                            <Box display="flex" alignItems="center">
                                <CancelIcon color="error" sx={{mr: 1}}/>
                                <Typography>Всего записей</Typography>
                            </Box>
                            <Box display="flex" alignItems="center">
                                <Typography variant="body1" fontWeight="medium" mr={1}>
                                    {difference.total > 0 ? '+' : ''}{difference.total}
                                </Typography>
                                <Chip
                                    size="small"
                                    label={`${difference.percent > 0 ? '+' : ''}${difference.percent.toFixed(1)}%`}
                                    color={difference.percent > 0 ? "success" : "error"}
                                    variant="outlined"
                                />
                            </Box>
                        </Box>
                    </Grid>
                </Grid>
            </CardContent>
        </Card>
    );
};

export default TodayVsYesterdayCard;
