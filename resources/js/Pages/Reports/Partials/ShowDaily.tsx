import React from 'react';
import {
    Box, Card, CardContent, Typography, Paper, Table, TableBody,
    TableCell, TableContainer, TableHead, TableRow, Chip, Divider
} from '@mui/material';
import {
    TrendingUp, TrendingDown, CalendarToday, Language, BarChart
} from '@mui/icons-material';
import Grid from "@mui/material/Grid2";
import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import {ReportTableProps} from "@/types/report";
dayjs.extend(customParseFormat);

const formatDate = (dateString: string) => {
    const date = dayjs(dateString, 'DD.MM.YYYY HH:mm:ss');
    return date.format('DD.MM.YY');
};

const ReportTable = ({report}: {
    report: ReportTableProps
}) => {
    const {daily, domains, metrics, period, total} = report;
    return (
        <Box>
            <Box sx={{mb: 3, display: 'flex', alignItems: 'center', p: 2}} component={Paper}>
                <CalendarToday sx={{mr: 1, color: 'primary.main'}}/>
                <Typography variant="h5" component="h2">
                    {period.label}: {period.start} - {period.end}
                </Typography>
            </Box>

            <Grid container spacing={3} sx={{mb: 4}}>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                    md: 3,
                }}>
                    <Card sx={{height: '100%', bgcolor: 'primary.light', color: 'primary.contrastText'}}>
                        <CardContent>
                            <Typography variant="subtitle2" gutterBottom>Visits</Typography>
                            <Typography variant="h4">{parseInt(total.count_total).toLocaleString()}</Typography>
                        </CardContent>
                    </Card>
                </Grid>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                    md: 3,
                }}>
                    <Card sx={{height: '100%', bgcolor: 'success.light', color: 'success.contrastText'}}>
                        <CardContent>
                            <Typography variant="subtitle2" gutterBottom>Confirm</Typography>
                            <Typography variant="h4">{parseInt(total.count_confirmed).toLocaleString()}</Typography>
                            <Typography variant="body2">
                                {(parseInt(total.count_confirmed) / parseInt(total.count_total) * 100).toFixed(1)}%
                            </Typography>
                        </CardContent>
                    </Card>
                </Grid>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                    md: 3,
                }}>
                    <Card sx={{height: '100%', bgcolor: 'warning.light', color: 'warning.contrastText'}}>
                        <CardContent>
                            <Typography variant="subtitle2" gutterBottom>Bad</Typography>
                            <Typography variant="h4">{parseInt(total.count_not_confirmed).toLocaleString()}</Typography>
                            <Typography variant="body2">
                                {(parseInt(total.count_not_confirmed) / parseInt(total.count_total) * 100).toFixed(1)}%
                            </Typography>
                        </CardContent>
                    </Card>
                </Grid>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                    md: 3,
                }}>
                    <Card sx={{height: '100%', bgcolor: 'info.light', color: 'info.contrastText'}}>
                        <CardContent>
                            <Typography variant="subtitle2" gutterBottom>Conversion</Typography>
                            <Typography variant="h4">{metrics.conversion_rate}%</Typography>
                            <Box sx={{display: 'flex', alignItems: 'center'}}>
                                {parseFloat(metrics.conversion_rate) > 50 ?
                                    <TrendingUp fontSize="small" sx={{mr: 0.5}}/> :
                                    <TrendingDown fontSize="small" sx={{mr: 0.5}}/>}
                                <Typography variant="body2">For {metrics.days_count} days</Typography>
                            </Box>
                        </CardContent>
                    </Card>
                </Grid>
            </Grid>

            <Box sx={{mb: 4}}>
                <Paper sx={{p: 2}}>
                    <Typography variant="h6" gutterBottom sx={{display: 'flex', alignItems: 'center'}}>
                        <BarChart sx={{mr: 1}}/> Total metrics
                    </Typography>
                    <Divider sx={{my: 2}}/>
                    <Grid container spacing={2}>
                        <Grid size={{
                            xs: 12,
                            sm: 4,
                        }}>
                            <Box sx={{textAlign: 'center'}}>
                                <Typography variant="subtitle2" color="text.secondary">Days</Typography>
                                <Typography variant="h5">{metrics.days_count}</Typography>
                            </Box>
                        </Grid>
                        <Grid size={{
                            xs: 12,
                            sm: 4,
                        }}>
                            <Box sx={{textAlign: 'center'}}>
                                <Typography variant="subtitle2" color="text.secondary">AVG daily</Typography>
                                <Typography variant="h5">{metrics.avg_daily_visits}</Typography>
                            </Box>
                        </Grid>
                        <Grid size={{
                            xs: 12,
                            sm: 4,
                        }}>
                            <Box sx={{textAlign: 'center'}}>
                                <Typography variant="subtitle2" color="text.secondary">Conversion</Typography>
                                <Typography variant="h5">{metrics.conversion_rate}%</Typography>
                            </Box>
                        </Grid>
                    </Grid>
                </Paper>
            </Box>
            <Grid container spacing={2}>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                }}>
                    <Box sx={{mb: 4}}>
                        <Paper>
                            <Box sx={{p: 2}}>
                                <Typography variant="h6" gutterBottom sx={{display: 'flex', alignItems: 'center'}}>
                                    <CalendarToday sx={{mr: 1}}/> Per day
                                </Typography>
                            </Box>
                            <TableContainer>
                                <Table sx={{minWidth: 650}} size="small">
                                    <TableHead>
                                        <TableRow sx={{bgcolor: 'grey.100'}}>
                                            <TableCell>Date</TableCell>
                                            <TableCell align="right">Total</TableCell>
                                            <TableCell align="right">Good</TableCell>
                                            <TableCell align="right">Bad</TableCell>
                                            <TableCell align="right">% conversion</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {daily.map((day) => {
                                            const total = parseInt(day.count_total);
                                            const confirmed = parseInt(day.count_confirmed);
                                            const conversionRate = total > 0 ? (confirmed / total * 100).toFixed(1) : "0";

                                            return (
                                                <TableRow key={day.visit_date} hover>
                                                    <TableCell component="th" scope="row">
                                                        {formatDate(day.visit_date)}
                                                    </TableCell>
                                                    <TableCell align="right">{total.toLocaleString()}</TableCell>
                                                    <TableCell align="right">
                                                        {parseInt(day.count_confirmed).toLocaleString()}
                                                    </TableCell>
                                                    <TableCell align="right">
                                                        {parseInt(day.count_not_confirmed).toLocaleString()}
                                                    </TableCell>
                                                    <TableCell align="right">
                                                        <Chip
                                                            label={`${conversionRate}%`}
                                                            size="small"
                                                            color={parseFloat(conversionRate) > 50 ? "success" : "warning"}
                                                            variant="outlined"
                                                        />
                                                    </TableCell>
                                                </TableRow>
                                            );
                                        })}
                                    </TableBody>
                                </Table>
                            </TableContainer>
                        </Paper>
                    </Box>
                </Grid>
                <Grid size={{
                    xs: 12,
                    sm: 6,
                }}>
                    <Box sx={{mb: 4}}>
                        <Paper>
                            <Box sx={{p: 2}}>
                                <Typography variant="h6" gutterBottom sx={{display: 'flex', alignItems: 'center'}}>
                                    <Language sx={{mr: 1}}/> Domains
                                </Typography>
                            </Box>
                            <TableContainer>
                                <Table sx={{minWidth: 650}} size="small">
                                    <TableHead>
                                        <TableRow sx={{bgcolor: 'grey.100'}}>
                                            <TableCell>Date</TableCell>
                                            <TableCell align="right">Total</TableCell>
                                            <TableCell align="right">Good</TableCell>
                                            <TableCell align="right">Bad</TableCell>
                                            <TableCell align="right">% conversion</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {domains.map((domain) => {
                                            const total = parseInt(domain.count_total);
                                            const confirmed = parseInt(domain.count_confirmed);
                                            const conversionRate = total > 0 ? (confirmed / total * 100).toFixed(1) : "0";

                                            return (
                                                <TableRow key={domain.domain} hover>
                                                    <TableCell component="th" scope="row"
                                                               sx={{wordBreak: 'break-word', maxWidth: '200px'}}>
                                                        {domain.domain || 'Прямой переход'}
                                                    </TableCell>
                                                    <TableCell align="right">{total.toLocaleString()}</TableCell>
                                                    <TableCell align="right">
                                                        {parseInt(domain.count_confirmed).toLocaleString()}
                                                    </TableCell>
                                                    <TableCell align="right">
                                                        {parseInt(domain.count_not_confirmed).toLocaleString()}
                                                    </TableCell>
                                                    <TableCell align="right">
                                                        <Chip
                                                            label={`${conversionRate}%`}
                                                            size="small"
                                                            color={parseFloat(conversionRate) > 50 ? "success" : "warning"}
                                                            variant="outlined"
                                                        />
                                                    </TableCell>
                                                </TableRow>
                                            );
                                        })}
                                    </TableBody>
                                </Table>
                            </TableContainer>
                        </Paper>
                    </Box>
                </Grid>
            </Grid>
        </Box>
    );
};

export default ReportTable;
