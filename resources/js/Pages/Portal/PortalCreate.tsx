import Grid from "@mui/material/Grid2";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Paper from "@mui/material/Paper";
import TextInput from "@/Components/ui/TextInput";
import {FormControl, FormHelperText, InputLabel, MenuItem, OutlinedInput, Select} from "@mui/material";
import Button from "@mui/material/Button";
import React from "react";
import {useForm} from "@inertiajs/react";
import {PageProps} from "@/types";
import {Topic} from "@/types/topic";
import SelectLandings from "@/Components/ui/SelectLandings";

export default function PortalCreate({topics, landings}: PageProps<{ topics: Topic[], landings: GroupedLandings }>) {

    const {data, setData, post, processing, errors} = useForm({
        name: "",
        short_url: "",
        bot_url: "",
        note: "",
        topic_id: topics.length > 0 ? topics[0].id : 0,
        default_landings: [] as string[],
    })

    function submit(e: React.FormEvent) {
        e.preventDefault()
        post(route('portal.store'))
    }

    async function generateShortUrl() {
        const response = await fetch(route('short-url'))
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const code = await response.json();
        setData('short_url', code.short_url)
    }

    return (
        <DashboardLayout header={{
            title: 'Add Portal',
            buttons: [
                {
                    type: 'link',
                    href: route('portal.index'),
                    label: 'Back to Portals',
                }
            ],
        }}>
            <Grid container spacing={2}>
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
                        <FormControl variant="outlined" size={'small'} fullWidth>
                            <InputLabel htmlFor="partner-url">Short URL</InputLabel>
                            <OutlinedInput
                                required
                                id="partner-url"
                                label="Short URL"
                                name="short_url"
                                onChange={e => setData('short_url', e.target.value)}
                                value={data.short_url}
                                fullWidth
                                endAdornment={
                                    <Button onClick={() => generateShortUrl()}>Generate</Button>
                                }
                            />
                            {!!errors.short_url &&
                                <FormHelperText id="errors-short_url">{errors.short_url}</FormHelperText>}
                        </FormControl>
                        <SelectLandings
                            required
                            landings={landings}
                            defaultValue={data.default_landings || []}
                            onChangeSelect={(value: string[]) => setData('default_landings', value)}
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
                                        <MenuItem key={'topic' + topic.id} value={topic.id}>
                                            {topic.name}
                                        </MenuItem>
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
