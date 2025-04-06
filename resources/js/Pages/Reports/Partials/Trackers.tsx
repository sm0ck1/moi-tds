import {Chip, Table, TableBody, TableCell, TableContainer, TableHead, TableRow} from "@mui/material";
import React from "react";
import {TrackersStatProps} from "@/types/report";

const Trackers = (
    {
        trackers
    }: {
    trackers: TrackersStatProps[]
}) => {

    if (!trackers) { return null; }

    return (
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
                    {trackers.map((data) => {
                        const total = parseInt(data.count_total);
                        const confirmed = parseInt(data.count_confirmed);
                        const conversionRate = total > 0 ? (confirmed / total * 100).toFixed(1) : "0";

                        return (
                            <TableRow key={data.tracker} hover>
                                <TableCell component="th" scope="row"
                                           sx={{wordBreak: 'break-word', maxWidth: '200px'}}>
                                    {data.tracker || 'Прямой переход'}
                                </TableCell>
                                <TableCell align="right">{total.toLocaleString()}</TableCell>
                                <TableCell align="right">
                                    {parseInt(data.count_confirmed).toLocaleString()}
                                </TableCell>
                                <TableCell align="right">
                                    {parseInt(data.count_not_confirmed).toLocaleString()}
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
    );

}

export default Trackers;
