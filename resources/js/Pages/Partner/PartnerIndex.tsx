import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PageProps} from "@/types";
import {Partner} from "@/types/partner";
import Paper from "@mui/material/Paper";
import TableContainer from "@mui/material/TableContainer";
import Table from "@mui/material/Table";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import TableBody from "@mui/material/TableBody";
import {ButtonGroup} from "@mui/material";
import Button from "@mui/material/Button";
import EditIcon from '@mui/icons-material/Edit';
import Delete from "@mui/icons-material/Delete";
import VisibilityIcon from '@mui/icons-material/Visibility';
import VisibilityOffIcon from '@mui/icons-material/VisibilityOff';
import React, {Fragment, useState} from "react";
import {Link, useForm} from "@inertiajs/react";
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import ExpandLessIcon from '@mui/icons-material/ExpandLess';
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";


export default function PartnerIndex({partners}: PageProps<{ partners: Partner[] }>) {

    const {delete: destroy, processing} = useForm();
    const [showPassword, setShowPassword] = useState(0)
    const [showPartnerLinks, setPartnerLinks] = useState(0)

    return (
        <DashboardLayout header={{
            title: 'Partners',
            buttons: [{
                type: 'link',
                href: route('partners.create'),
                label: 'Add Partner',
            }],
            filters: [{
                type: 'link',
                href: route('partners.index'),
                label: 'All',
            }, {
                type: 'link',
                href: route('partners.index', {'trashed': 'only'}),
                label: 'Deleted',
            }],
        }}>
            <TableContainer component={Paper}>
                <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                    <TableHead>
                        <TableRow>

                            <TableCell>Name</TableCell>
                            <TableCell>URL</TableCell>
                            <TableCell>Login</TableCell>
                            <TableCell align={'center'}>Password</TableCell>
                            <TableCell></TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {partners.map((row) => (
                            <Fragment key={row.name}>
                                <TableRow
                                    sx={{'&:last-child td, &:last-child th': {border: 0}}}
                                >

                                    <TableCell component="th" scope="row">
                                        {row.name}
                                    </TableCell>
                                    <TableCell>{row.url}</TableCell>
                                    <TableCell>{row.login}</TableCell>
                                    <TableCell

                                        sx={{width: '300px'}}
                                    >
                                        <Button variant='text' onClick={() => {
                                            if (showPassword === row.id) {
                                                setShowPassword(0)
                                                return
                                            }
                                            setShowPassword(row.id)
                                        }} sx={{mr: 1}}>
                                            {showPassword !== row.id && <VisibilityIcon fontSize="small"/>}
                                            {showPassword === row.id && <VisibilityOffIcon fontSize="small"/>}
                                        </Button>
                                        {showPassword === row.id && row.password}
                                        {showPassword !== row.id && '******************'}
                                    </TableCell>
                                    <TableCell align={"right"}>
                                        <ButtonGroup>
                                            {!row.deleted_at && (
                                                <>
                                                    {row.partner_links && row.partner_links.length > 0 &&
                                                        <Button variant='text' onClick={() => {
                                                            if (showPartnerLinks === row.id) {
                                                                setPartnerLinks(0)
                                                                return
                                                            }
                                                            setPartnerLinks(row.id)
                                                        }}>
                                                            {showPartnerLinks !== row.id ?
                                                                <ExpandMoreIcon rotate={60}/> : <ExpandLessIcon/>}
                                                        </Button>
                                                    }
                                                    <Button component={Link} variant='text'
                                                            href={route('partners.edit', row.id)}>
                                                        <EditIcon fontSize="small"/>
                                                    </Button>
                                                    <Button variant='text' loading={processing} onClick={() => {
                                                        if (confirm('Are you sure?')) {
                                                            destroy(route('partners.destroy', row.id));
                                                        }
                                                    }}>
                                                        <Delete fontSize="small"/>
                                                    </Button>
                                                </>
                                            )}
                                            {row.deleted_at && (
                                                <Button variant='text' onClick={() => {
                                                    if (confirm('Are you sure?')) {
                                                        destroy(route('partners.restore', row.id));
                                                    }
                                                }}>
                                                    Restore
                                                </Button>
                                            )}
                                        </ButtonGroup>
                                    </TableCell>
                                </TableRow>
                                {showPartnerLinks === row.id && row.partner_links && row.partner_links.length > 0 && (
                                    <TableRow sx={{
                                        backgroundColor: '#f9f9f9',
                                        my: 4,
                                    }}>
                                        <TableCell colSpan={5}>
                                            <Box sx={{display: 'flex', flexDirection: 'column', gap: 2}}>
                                                {row.partner_links.map((link) => (
                                                    <Box key={link.id} sx={{
                                                        display: 'flex',
                                                        flexDirection: 'row',
                                                        gap: 2,
                                                        alignItems: 'center',
                                                        ml: 2
                                                    }}>
                                                        <Typography variant={'h6'}>{link.topic.name}</Typography>
                                                        <Typography variant={'body1'}>{link.name}</Typography>
                                                    <Typography variant={'body2'}>{link.url}</Typography>
                                                    </Box>
                                                ))}
                                            </Box>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </Fragment>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
        </DashboardLayout>
    )
}
