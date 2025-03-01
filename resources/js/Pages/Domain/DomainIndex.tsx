import DashboardLayout from "@/Components/shared/DashboardLayout";

import Paper from "@mui/material/Paper";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import TableCell from "@mui/material/TableCell";
import React, {useState} from "react";
import TableContainer from "@mui/material/TableContainer";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import {PageProps} from "@/types";
import {Domain} from "@/types/domain";
import {ButtonGroup, Switch} from "@mui/material";
import Button from "@mui/material/Button";
import EditIcon from "@mui/icons-material/Edit";
import {Link, router, useForm} from "@inertiajs/react";
import Delete from "@mui/icons-material/Delete";

type DomainProps = PageProps<{ domains: Domain[] }>

export default function DomainIndex({domains}: DomainProps) {

    const {delete: destroy, processing} = useForm();

    const [loading, setLoading] = useState(0);

    async function changeCheckboxes(id: number, value: boolean, column: string) {
        setLoading(id);
        try {
            const response = await fetch(route('api-domain.edit', id), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({[column]: value}),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            router.reload({
                only: ['domains'],
                onSuccess: () => {
                    setLoading(0);
                }
            });

        } catch (error) {
            console.error('Update failed', error);
        }
    }

    return (
        <DashboardLayout header={{
            title: 'Domains',
            buttons: [
                {
                    type: 'link',
                    href: route('domain.create'),
                    label: 'Add Domain',
                }, {
                    type: 'link',
                    href: route('domain.index', {'trashed': 'only'}),
                    label: 'Deleted',
                }
            ],
        }}>
            <TableContainer component={Paper}>
                <Table sx={{minWidth: 650}} size="small" aria-label="a dense table">
                    <TableHead>
                        <TableRow>

                            <TableCell>Name</TableCell>
                            <TableCell>Ping</TableCell>
                            <TableCell>Code TDS</TableCell>
                            <TableCell>DNS Provider</TableCell>
                            <TableCell>DNS Login</TableCell>
                            <TableCell>Reg start</TableCell>
                            <TableCell>Reg end</TableCell>
                            <TableCell></TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {domains && domains.length > 0 && domains.map((domain) => (
                            <TableRow key={domain.id}>
                                <TableCell component="th" scope="row">
                                    {domain.name}
                                </TableCell>
                                <TableCell>
                                    <Switch
                                        disabled={loading === domain.id}
                                        checked={!!domain.is_active_for_ping}
                                        onChange={async (event) => {
                                            await changeCheckboxes(domain.id, event.target.checked, 'is_active_for_ping')
                                        }}
                                        color="primary"
                                    />
                                </TableCell>
                                <TableCell>
                                    <Switch
                                        disabled={loading === domain.id}
                                        checked={!!domain.is_active_for_code}
                                        onChange={async (event) => {
                                            await changeCheckboxes(domain.id, event.target.checked, 'is_active_for_code')
                                        }}
                                        color="primary"
                                    />
                                </TableCell>
                                <TableCell>{domain.dns_provider}</TableCell>
                                <TableCell>{domain.dns_provider_login}</TableCell>
                                <TableCell>{domain.date_registration}</TableCell>
                                <TableCell>{domain.date_end}</TableCell>

                                <TableCell align={'right'}>
                                    <ButtonGroup>
                                        <Button component={Link} href={route('domain.edit', domain.id)}>
                                            <EditIcon/>
                                        </Button>
                                        <Button loading={processing} onClick={() => {
                                            if (confirm('Are you sure you want to delete this domain?')) {
                                                destroy(route('domain.destroy', domain.id))
                                            }
                                        }}>
                                            <Delete/>
                                        </Button>
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
