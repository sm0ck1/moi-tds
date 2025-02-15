import DashboardLayout from "@/Components/shared/DashboardLayout";
import TextInput from "@/Components/ui/TextInput";
import {Partner} from "@/types/partner";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import {useForm} from "@inertiajs/react";
import React from "react";
import Button from "@mui/material/Button";
import PartnerLinksList from "@/Pages/Partner/Partials/PartnerLinksList";
import Typography from "@mui/material/Typography";
import PartnerLinksCreate from "@/Pages/Partner/Partials/PartnerLinksCreate";
import Box from "@mui/material/Box";
import {Topic} from "@/types/topic";
import {CountriesDict} from "@/types/country";

export default function PartnerEdit({partner, topics, countries}: {
    partner: Partner,
    topics: Topic[],
    countries: CountriesDict,
}) {

    const {data, setData, patch, processing, errors} = useForm({
        name: "",
        url: "",
        login: "",
        password: "",
        note: "",
    })

    React.useEffect(() => {
        setData('name', partner.name)
        setData('url', partner.url)
        setData('login', partner.login)
        setData('password', partner.password)
        setData('note', partner.note)
    }, [partner])

    function submit(e: React.FormEvent) {
        e.preventDefault()
        patch(route('partners.update', partner.id))
    }

    return (
        <DashboardLayout header={{
            title: 'Edit Partner',
            buttons: [
                {
                    type: 'link',
                    href: route('partners.index'),
                    label: 'Back to Partners',
                },
                {
                    type: 'link',
                    href: route('partners.create'),
                    label: 'Add partner',
                }
            ],
        }}>
            <Grid container spacing={2}>
                <Grid size={{xs: 12, md: 6}}>
                    <Box sx={{
                        gap: 2,
                        display: 'flex',
                        flexDirection: 'column',
                        width: '100%',
                    }}>
                        <Typography variant='h6'>Partner</Typography>
                        <Paper sx={{
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
                                fullWidth
                                multiline
                                rows={4}
                                textError={errors.note}
                            >{data.note}</TextInput>
                            <Button
                                type="submit"
                                fullWidth
                                variant="contained"
                                sx={{mt: 2}}
                                onClick={submit}
                                disabled={processing}
                            >
                                Save
                            </Button>
                        </Paper>
                    </Box>
                </Grid>
                <Grid size={{xs: 12, md: 6}}>
                    <Box sx={{
                        overflow: 'hidden',
                        gap: 2,
                        display: 'flex',
                        flexDirection: 'column',
                    }}>
                        <Typography variant='h6'>Partner links</Typography>
                        <PartnerLinksCreate topics={topics} partner={partner} countries={countries}/>
                        {partner.partner_links && partner.partner_links.length > 0 &&
                            <PartnerLinksList
                                partnerLinks={partner.partner_links}
                                topics={topics}
                                countries={countries}
                            />
                        }
                    </Box>
                </Grid>
            </Grid>
        </DashboardLayout>
    )

}
