import {PageProps} from "@/types";
import {VisitUser} from "@/types/visit-user";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Paper from "@mui/material/Paper";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import React, {Fragment, useEffect, useState} from "react";
import TableContainer from "@mui/material/TableContainer";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import {PusherChannels, PusherEvents, PusherService} from "@/services/pusher";
import {router} from "@inertiajs/react";
import Box from "@mui/material/Box";
import {PaginationInterface} from "@/types/pagination";
import Pagination from '@mui/material/Pagination';
import Typography from "@mui/material/Typography";

type VisitUsersIndexProps = PageProps<{ visitUsers: PaginationInterface<VisitUser> }>

export default function VisitUsers({visitUsers: initialVisitUsers, pusher}: VisitUsersIndexProps) {

    const [visitUsers, setVisitUsers] = useState(initialVisitUsers);

    const pusherService = new PusherService(pusher.key)
    useEffect(() => {
        pusherService.subscribe(PusherChannels.visitUser, PusherEvents.newVisit, (data: any) => {
            router.visit(route('visits.index'), {
                preserveState: true,
                preserveScroll: true,
                only: ['visitUsers'], // Запрашиваем только часть данных
                onSuccess: (page) => {
                    setVisitUsers(page.props.visitUsers as PaginationInterface<VisitUser>);
                },
            });
        });

        return () => {
            pusherService.unsubscribe(PusherChannels.visitUser, PusherEvents.newVisit);
        };

    }, []);

    const handlePageChange = (_: React.ChangeEvent<unknown>, page: number) => {
        router.get(route('visits.index', {page}));
    };

    return (
        <DashboardLayout header={{
            title: 'Visit Users',
        }}>

            <TableContainer component={Paper}>
                <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                    <TableHead>
                        <TableRow>
                            <TableCell>IP Address</TableCell>
                            <TableCell>Visit count</TableCell>
                            <TableCell>Tracker</TableCell>
                            <TableCell>Portal</TableCell>
                            <TableCell>Visit Date</TableCell>
                            <TableCell>Country Code</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {visitUsers && visitUsers.data.map((visit) => (
                            <Fragment key={visit.id}>
                                <TableRow
                                    sx={{'&:last-child td, &:last-child th': {border: 0}}}
                                >
                                    <TableCell component="th" scope="row"
                                               sx={{display: 'flex', alignItems: 'center', gap: 1}}>
                                        {/* Green icon with confirm or not*/}
                                        {visit.confirm_click === 1 ? '✅' : '❌'}
                                        <a href={"https://ip-api.com/#" + visit.ip_address}
                                           target="_blank"
                                        >{visit.ip_address}</a>
                                    </TableCell>
                                    <TableCell>{visit.visit_count}</TableCell>
                                    <TableCell>{visit.tracker}</TableCell>
                                    <TableCell>{visit.portal.short_url}</TableCell>
                                    <TableCell>{visit.created_at}</TableCell>
                                    <TableCell>{visit.country_code}</TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell colSpan={9}>
                                        <Box sx={{p: 2}}>
                                            <Box>
                                                <b>Device</b>: {visit.device_type}
                                            </Box>
                                            <Box>
                                                <b>User Agent</b>: {visit.user_agent}
                                            </Box>
                                            <Box>
                                                <b>Portal</b>: {visit.portal.name}
                                            </Box>
                                            <Box>
                                                <b>Referrer</b>: {visit.referrer}
                                            </Box>
                                            <Box>
                                                <b>Partner</b>: {visit.portal_partner_link?.partner.name}
                                            </Box>
                                            <Box>
                                                <b>Partner
                                                    Link</b>: {visit.portal_partner_link?.name} {visit.portal_partner_link?.url}
                                            </Box>
                                            <Box>
                                                <b>Redirected to</b>: {visit.external_url}
                                            </Box>
                                            <Box sx={{display: 'flex', alignItems: 'center', gap: 2}}>
                                                <b>Metrics:</b>
                                                {visit.metrics && Object.entries(visit.metrics).map(([key, value]: any) => (
                                                    <Box component="span" key={key}>
                                                        <Typography component="span" fontWeight="bold" variant={'body2'}>
                                                            {key.charAt(0).toUpperCase() + key.slice(1)}:
                                                        </Typography>
                                                        {' '}
                                                        <Typography component="span" variant={'body2'}>
                                                            {typeof value === 'object' ?
                                                                Object.entries(value).map(([k, v]) => (
                                                                    <Box component="span" key={k} sx={{ ml: 1 }}>
                                                                        {k}: {String(v)}{' '}
                                                                    </Box>
                                                                ))
                                                                : String(value)
                                                            }
                                                        </Typography>
                                                    </Box>
                                                ))}
                                            </Box>
                                        </Box>
                                    </TableCell>
                                </TableRow>
                            </Fragment>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
            <Paper sx={{display: 'flex', justifyContent: 'center', mt: 2, p: 2}}>
                <Pagination
                    count={visitUsers.last_page}
                    page={visitUsers.current_page}
                    onChange={handlePageChange}
                    color="primary"
                />
            </Paper>

        </DashboardLayout>
    );
}
