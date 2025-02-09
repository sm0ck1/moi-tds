import DashboardLayout from "@/Components/shared/DashboardLayout";
import TableContainer from "@mui/material/TableContainer";
import Paper from "@mui/material/Paper";
import Table from "@mui/material/Table";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import TableBody from "@mui/material/TableBody";
import Button from "@mui/material/Button";
import DeviceHubIcon from '@mui/icons-material/DeviceHub';
import {ButtonGroup} from "@mui/material";
import {Link, useForm} from "@inertiajs/react";
import EditIcon from "@mui/icons-material/Edit";
import Delete from "@mui/icons-material/Delete";
import {PageProps} from "@/types";
import {Portal} from "@/types/portal";
import {Info} from "@mui/icons-material";
import Box from "@mui/material/Box";
import Tooltip from "@mui/material/Tooltip";
import TextInput from "@/Components/ui/TextInput";

export default function PortalIndex({portals}: PageProps<{ portals: Portal[] }>) {
    const {delete: destroy, processing} = useForm();
    return (
        <DashboardLayout header={{
            title: 'Portals',
            buttons: [{
                type: 'link',
                href: route('portal.create'),
                label: 'Add Portal',
            }],
            filters: [{
                type: 'link',
                href: route('portal.index'),
                label: 'All',
            }, {
                type: 'link',
                href: route('portal.index', {'trashed': 'only'}),
                label: 'Deleted',
            }],
        }}>
            <TableContainer component={Paper}>
                <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                    <TableHead>
                        <TableRow>
                            <TableCell>Name</TableCell>
                            <TableCell>Short URL</TableCell>
                            <TableCell>Topic</TableCell>
                            <TableCell>Flows</TableCell>
                            <TableCell></TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {portals.map((row) => (
                            <TableRow
                                key={row.name}
                                sx={{
                                    '&:last-child td, &:last-child th': {border: 0},

                                }}
                            >

                                <TableCell component="th" scope="row">
                                    <Box sx={{display: 'flex', alignItems: 'center', gap: 1}}>
                                        <Tooltip title={row.note}>
                                        <Info fontSize="small" color={'info'}/>
                                        </Tooltip>
                                        {row.name}
                                    </Box>
                                </TableCell>
                                <TableCell>
                                    <TextInput
                                        label=""
                                        value={row.short_url}
                                        disabled
                                        size={'small'}
                                    />
                                </TableCell>
                                <TableCell>{row.topic.name}</TableCell>
                                <TableCell>{row.portal_partner_links_count}</TableCell>

                                <TableCell align={"right"}>
                                    <ButtonGroup>
                                        <Button component={Link} variant='text'
                                                href={route('portal.show', row.id)}>
                                            <DeviceHubIcon fontSize="small"/>
                                        </Button>
                                        {!row.deleted_at && (
                                            <>
                                                <Button component={Link} variant='text'
                                                        href={route('portal.edit', row.id)}>
                                                    <EditIcon fontSize="small"/>
                                                </Button>
                                                <Button variant='text' loading={processing} onClick={() => {
                                                    if (confirm('Are you sure?')) {
                                                        destroy(route('portal.destroy', row.id));
                                                    }
                                                }}>
                                                    <Delete fontSize="small"/>
                                                </Button>
                                            </>
                                        )}
                                    </ButtonGroup>
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
        </DashboardLayout>
    )
}
