import DashboardLayout from "@/Components/shared/DashboardLayout";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import {useForm} from "@inertiajs/react";
import React from "react";
import TextInput from "@/Components/ui/TextInput";
import AppBar from "@mui/material/AppBar";
import Button from "@mui/material/Button";
import {DateTimePicker} from "@mui/x-date-pickers";
import dayjs from "dayjs";
import {Switch} from "@mui/material";
import FormControlLabel from "@mui/material/FormControlLabel";

export default function DomainCreate() {

    const {data, setData, post, processing, errors} = useForm({
        name: "",
        date_registration: "",
        date_end: "",
        note: "",
        dns_provider: "",
        dns_provider_login: "",
        is_active_for_ping: 0,
        is_active_for_code: 0
    })

    function submit(e: React.FormEvent) {
        e.preventDefault()
        post(route('domain.store'))
    }

    return (
        <DashboardLayout header={{
            title: 'Add Domain',
            buttons: [
                {
                    type: 'link',
                    href: route('domain.index'),
                    label: 'Back',
                }
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
                            id="topic-name"
                            label="Name"
                            name="name"
                            onChange={e => setData('name', e.target.value)}
                            value={data.name}
                            fullWidth
                            textError={errors.name}
                        />
                        <DateTimePicker
                            slotProps={{textField: {size: 'small'}}}
                            views={['year', 'month', 'day']}
                            label="Date Registration"
                            name="date_registration"
                            defaultValue={dayjs(data.date_registration)}
                            onChange={date => {
                                setData('date_registration', date ? date.toISOString() : '')
                                setData('date_end', date ? date.add(1, 'year').toISOString() : '')
                            }}

                        />
                        <DateTimePicker
                            slotProps={{textField: {size: 'small'}}}
                            views={['year', 'month', 'day']}
                            label="Date End"
                            name="date_end"
                            defaultValue={dayjs(data.date_end)}
                            onChange={date => setData('date_end', date ? date.toISOString() : data.date_registration)}
                            value={dayjs(data.date_end)}
                        />
                        <TextInput
                            id="note"
                            label="Note"
                            name="note"
                            onChange={e => setData('note', e.target.value)}
                            value={data.note}
                            fullWidth
                            textError={errors.note}
                        />
                        <TextInput
                            id="dns_provider"
                            label="DNS Provider"
                            name="dns_provider"
                            onChange={e => setData('dns_provider', e.target.value)}
                            value={data.dns_provider}
                            fullWidth
                            textError={errors.dns_provider}
                        />
                        <TextInput
                            id="dns_provider_login"
                            label="DNS Provider Login"
                            name="dns_provider_login"
                            onChange={e => setData('dns_provider_login', e.target.value)}
                            value={data.dns_provider_login}
                            fullWidth
                            textError={errors.dns_provider_login}
                        />
                        <FormControlLabel
                            control={
                                <Switch
                                    checked={!!data.is_active_for_ping}
                                    onChange={async (event) => {
                                        setData('is_active_for_ping', event.target.checked ? 1 : 0)
                                    }}
                                    color="primary"
                                />
                            } label="Is Active For Ping"/>
                        <FormControlLabel
                            control={
                                <Switch
                                    checked={!!data.is_active_for_code}
                                    onChange={async (event) => {
                                        setData('is_active_for_code', event.target.checked ? 1 : 0)
                                    }}
                                    color="primary"
                                />
                            } label="Is Active For Code"/>
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
