import DashboardLayout from "@/Components/shared/DashboardLayout";
import Paper from "@mui/material/Paper";
import Grid from "@mui/material/Grid2";
import TextInput from "@/Components/ui/TextInput";
import {useForm} from "@inertiajs/react";
import React from "react";
import AppBar from "@mui/material/AppBar";
import Button from "@mui/material/Button";

export default function PartnerCreate() {

    const {data, setData, post, processing, errors} = useForm({
        name: "",
        url: "",
        login: "",
        password: "",
        note: "",
    })

    function submit(e: React.FormEvent) {
        e.preventDefault()
        post(route('partners.store'))
    }

    return (
        <DashboardLayout header={{
            title: 'Create Partner',
            buttons: [
                {
                    type: 'link',
                    href: route('partners.index'),
                    label: 'Back to Partners',
                },
            ],
        }}>
            <Grid container>
                <Grid size={{xs: 12, md: 6}}>
                    <Paper sx={{
                        margin: 'auto',
                        overflow: 'hidden',
                        padding: 2,
                        gap: 2,
                        display: 'flex',
                        flexDirection: 'column',
                    }}>
                        <TextInput
                            required
                            id="partner-name"
                            label="Name"
                            name="name"
                            onChange={e => setData('name', e.target.value)}
                            value={data.name}
                            fullWidth
                            textError={errors.name}
                        />
                        <TextInput
                            required
                            id="partner-url"
                            label="URL"
                            name="url"
                            onChange={e => setData('url', e.target.value)}
                            value={data.url}
                            fullWidth
                            textError={errors.url}
                        />
                        <TextInput
                            required
                            id="partner-login"
                            label="Login"
                            name="login"
                            onChange={e => setData('login', e.target.value)}
                            value={data.login}
                            fullWidth
                            textError={errors.login}
                        />
                        <TextInput
                            required
                            id="partner-password"
                            label="Password"
                            name="password"
                            onChange={e => setData('password', e.target.value)}
                            value={data.password}
                            fullWidth
                            textError={errors.password}
                        />
                        <TextInput
                            id="partner-note"
                            label="Note"
                            name="note"
                            onChange={e => setData('note', e.target.value)}
                            value={data.note}
                            fullWidth
                            multiline
                            rows={4}
                            textError={errors.note}
                        />
                        <AppBar position="static" color="default" elevation={0}>
                            <Button
                                loading={processing}
                                onClick={submit}
                                sx={{
                                    width: '100%',
                                    borderRadius: 0,
                                }}
                            >
                                Save
                            </Button>
                        </AppBar>
                    </Paper>
                </Grid>
            </Grid>
        </DashboardLayout>
    )
}
