import {PageProps} from "@/types";
import {VisitUser} from "@/types/visit-user";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Paper from "@mui/material/Paper";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import React, {Fragment, useEffect} from "react";
import TableContainer from "@mui/material/TableContainer";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import {PusherChannels, PusherEvents, PusherService} from "@/services/pusher";
import {router} from "@inertiajs/react";
import Box from "@mui/material/Box";
import {PaginationInterface} from "@/types/pagination";
import Pagination from '@mui/material/Pagination';

type VisitUsersIndexProps = PageProps<{ visitUsers: PaginationInterface<VisitUser> }>

export default function VisitUsers({visitUsers, pusher}: VisitUsersIndexProps) {
    const pusherService = new PusherService(pusher.key)
    useEffect(() => {
        pusherService.subscribe(PusherChannels.visitUser, PusherEvents.newVisit, (data: any) => {
            router.reload();
        });

        return () => {
            pusherService.unsubscribe(PusherChannels.visitUser, PusherEvents.newVisit);
        };

    }, []);

    const handlePageChange = (_: React.ChangeEvent<unknown>, page: number) => {
        router.get(route('visits.index', { page }));
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
                                    <TableCell component="th" scope="row">
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
                                                <b>Partner Link</b>: {visit.portal_partner_link?.name} {visit.portal_partner_link?.url}
                                            </Box>
                                            <Box>
                                                <b>Redirected to</b>: {visit.external_url}
                                            </Box>
                                        </Box>
                                    </TableCell>
                                </TableRow>
                            </Fragment>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
            <Paper sx={{ display: 'flex', justifyContent: 'center', mt: 2, p: 2 }}>
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
