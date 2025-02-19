import TableContainer from "@mui/material/TableContainer";
import Paper from "@mui/material/Paper";
import Table from "@mui/material/Table";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import TableBody from "@mui/material/TableBody";
import Box from "@mui/material/Box";
import Tooltip from "@mui/material/Tooltip";
import {Info} from "@mui/icons-material";
import {PaginationInterface} from "@/types/pagination";
import {PortalPlacement} from "@/types/portalPlacement";

type PortalPlacementsIndexProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
}

export default function PortalPlacementIndex({portalPlacements}: PortalPlacementsIndexProps) {
    return (
        <TableContainer component={Paper}>
            <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                <TableHead>
                    <TableRow>
                        <TableCell>ID</TableCell>
                        <TableCell>Ping Counter</TableCell>
                        <TableCell>Portal</TableCell>
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
                            <TableCell component="th" scope="row">
                                {row.id}
                            </TableCell>
                            <TableCell>
                                <Box display="flex" alignItems="center">
                                    {row.ping_counter}
                                    {row.updated_at !== row.created_at ? " Last ping: " + row.updated_at : ""}
                                </Box>
                            </TableCell>
                            <TableCell>{row.portal.name}</TableCell>
                            <TableCell>{row.external_url}</TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </TableContainer>
    );
}
