import {Portal} from "@/types/portal";
import {PageProps} from "@/types";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import TextInput from "@/Components/ui/TextInput";
import {useForm} from "@inertiajs/react";
import React from "react";
import Button from "@mui/material/Button";
import {Topic} from "@/types/topic";
import {FormControl, InputLabel, MenuItem, Select} from "@mui/material";
import Typography from "@mui/material/Typography";
import PortalPartnerLinks from "@/Pages/Portal/Partials/PortalPartnerLinks";
import {CountriesDict} from "@/types/country";
import SelectLandings from "@/Components/ui/SelectLandings";

type PortalEditProps = {
    portal: Portal,
    topics: Topic[],
    partnerLinks: PartnerLink[],
    countries: CountriesDict,
    landings: GroupedLandings,
}

export default function PortalEdit({portal, topics, partnerLinks, countries, landings}: PageProps<PortalEditProps>) {

    const {data, setData, patch, processing, errors} = useForm({
        name: "",
        short_url: "",
        bot_url: "",
        note: "",
        topic_id: topics.length > 0 ? topics[0].id : 0,
        default_lendings: [] as string[],
    })

    React.useEffect(() => {
        setData('name', portal.name)
        setData('short_url', portal.short_url)
        setData('bot_url', portal.bot_url)
        setData('note', portal.note)
        setData('topic_id', portal.topic_id || 0)
        setData('default_lendings', portal.default_lendings || [])
    }, [portal])

    function submit(e: React.FormEvent) {
        e.preventDefault()
        patch(route('portal.update', portal.id))
    }

    return (
        <DashboardLayout header={{
            title: 'Edit Portal',
            buttons: [
                {
                    type: 'link',
                    href: route('portal.index'),
                    label: 'Back to Portals',
                },
                {
                    type: 'link',
                    href: route('portal.create'),
                    label: 'Add Portal',
                }
            ],
        }}>
            <Grid container spacing={2}>
                <Grid size={{xs: 12, md: 6}}>
                    <Paper>
                        <Typography variant="h6" p={2}>
                            Flows
                        </Typography>
                    </Paper>
                    <PortalPartnerLinks
                        landings={landings}
                        countries={countries}
                        portalId={portal.id}
                        partnerLinks={partnerLinks}
                        portalPartnerLinks={portal.portal_partner_links || []}
                    />
                </Grid>
                <Grid size={{xs: 12, md: 6}}>
                    <Paper sx={{
                        margin: 'auto',
                        overflow: 'hidden',
                        padding: 2,
                        gap: 3,
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
                            label="Short URL"
                            name="short_url"
                            onChange={e => setData('short_url', e.target.value)}
                            value={data.short_url}
                            fullWidth
                            textError={errors.short_url}
                        />
                        <TextInput
                            required
                            id="partner-bot-url"
                            label="Bot URL"
                            name="bot_url"
                            onChange={e => setData('bot_url', e.target.value)}
                            value={data.bot_url}
                            fullWidth
                            textError={errors.bot_url}
                        />
                        <TextInput
                            required
                            id="partner-note"
                            label="Note"
                            name="note"
                            onChange={e => setData('note', e.target.value)}
                            value={data.note}
                            fullWidth
                            textError={errors.note}
                        />
                        <SelectLandings
                            required
                            landings={landings}
                            defaultValue={data.default_lendings || []}
                            onChangeSelect={(value: string[]) => setData('default_lendings', value)}
                        />
                        {topics && (
                            <FormControl fullWidth>
                                <InputLabel id="topic-list">Topic</InputLabel>

                                <Select
                                    labelId="topic-list"
                                    label={'Topic'}
                                    variant={'outlined'}
                                    fullWidth
                                    value={data.topic_id}
                                    size={'small'}
                                    onChange={e => setData('topic_id', e.target.value as number)}
                                    sx={{mb: 2}}
                                >
                                    <MenuItem value="">
                                        <em>None</em>
                                    </MenuItem>
                                    {topics.map(topic => (
                                        <MenuItem key={'topic' + topic.id} value={topic.id}>{topic.name}</MenuItem>
                                    ))}
                                </Select>
                            </FormControl>
                        )}
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
                </Grid>
            </Grid>
        </DashboardLayout>
    )
}
