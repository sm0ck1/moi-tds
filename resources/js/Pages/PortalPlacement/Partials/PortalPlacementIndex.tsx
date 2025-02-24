import TableContainer from "@mui/material/TableContainer";
import Paper from "@mui/material/Paper";
import Table from "@mui/material/Table";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import TableBody from "@mui/material/TableBody";
import Box from "@mui/material/Box";
import {PaginationInterface} from "@/types/pagination";
import {PortalPlacement} from "@/types/portalPlacement";
import HourglassEmptyIcon from '@mui/icons-material/HourglassEmpty';
import HourglassTopIcon from '@mui/icons-material/HourglassTop';
import HourglassFullIcon from '@mui/icons-material/HourglassFull';
import {Switch} from "@mui/material";
import {router} from "@inertiajs/react";
import React, {useState} from "react";
import Pagination from "@mui/material/Pagination";
import ReplayIcon from '@mui/icons-material/Replay';

type PortalPlacementsIndexProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
}

export default function PortalPlacementIndex({portalPlacements, ...counters}: PortalPlacementsIndexProps) {

    const [loading, setLoading] = useState(0);

    async function changeInSearch(id: number, inSearch: boolean) {
        setLoading(id);
        try {
            const response = await fetch(route('api-portal-placements.in_search', id), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({in_search: inSearch}),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            router.reload({
                only: ['portalPlacements', 'inSearch'],
                onSuccess: () => {
                    setLoading(0);
                }
            });

        } catch (error) {
            console.error('Update failed', error);
        }
    }

    async function pingAgain(id: number) {
        setLoading(id);
        try {
            const response = await fetch(route('api-portal-placements.ping-again', id), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            router.reload({
                only: ['portalPlacements', 'inSearch'],
                onSuccess: () => {
                    setLoading(0);
                }
            });

        } catch (error) {
            console.error('Update failed', error);
        }
    }

    const handlePageChange = (_: React.ChangeEvent<unknown>, page: number) => {
        router.get(route('portal-placements.index', {page}));
    };



    return (
        <>
            <TableContainer component={Paper}>
                <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                    <TableHead>
                        <TableRow>
                            <TableCell>Ping was</TableCell>
                            <TableCell>In search</TableCell>
                            <TableCell>Portal</TableCell>
                            <TableCell>Last update</TableCell>
                            <TableCell>LINK</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {portalPlacements && portalPlacements.data.map((row) => (
                            <TableRow
                                key={row.id}
                                sx={{
                                    '&:last-child td, &:last-child th': {border: 0},

                                }}
                            >
                                <TableCell>
                                    <Box display="flex" alignItems="center">
                                        {row.ping_counter === 0 && row.get_to_ping === 0 &&
                                            <HourglassEmptyIcon
                                                fontSize={'small'}
                                                color="primary"

                                            />
                                        }
                                        {row.ping_counter === 0 && row.get_to_ping === 1 &&
                                            <HourglassTopIcon fontSize={'small'} color="warning"/>}
                                        {row.ping_counter === 1 && row.get_to_ping === 1 &&
                                            <HourglassFullIcon fontSize={'small'} color="success"/>}

                                    </Box>
                                </TableCell>
                                <TableCell>
                                    <Switch
                                        disabled={loading === row.id}
                                        checked={row.in_search}
                                        onChange={async (event) => {
                                            changeInSearch(row.id, event.target.checked)
                                        }}
                                        color="primary"
                                    />
                                </TableCell>
                                <TableCell>{row.portal.name}</TableCell>
                                <TableCell>
                                    <Box sx={{display: 'flex', gap: 1}}>
                                        {row.updated_at}
                                        <Box sx={{
                                            cursor: 'pointer'
                                        }}
                                        onClick={()=>pingAgain(row.id)}
                                        >
                                            <ReplayIcon fontSize={'small'}/>
                                        </Box>
                                    </Box>
                                </TableCell>
                                <TableCell>
                                    <a href={"https://www.google.com/search?q=site:" + row.external_url} target="_blank"
                                       rel="noreferrer noopener">
                                        {row.external_url}
                                    </a>
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
            <Paper sx={{display: 'flex', justifyContent: 'center', mt: 2, p: 2}}>
                <Pagination
                    count={portalPlacements.last_page}
                    page={portalPlacements.current_page}
                    onChange={handlePageChange}
                    color="primary"
                />
            </Paper>
        </>
    );
}
