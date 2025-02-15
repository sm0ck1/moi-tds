import {Box, FormControl, FormHelperText, InputLabel, MenuItem, Paper, Select, Typography} from "@mui/material";
import TextField from "@mui/material/TextField";
import {useForm} from "@inertiajs/react";
import Button from "@mui/material/Button";
import {Topic} from "@/types/topic";
import React, {useEffect} from "react";
import {Partner} from "@/types/partner";
import {CountriesDict} from "@/types/country";
import SelectCountries from "@/Components/ui/SelectCountries";

export default function PartnerLinksCreate({partner, topics, countries}: { partner: Partner, topics: Topic[], countries: CountriesDict }) {

    const {data, setData, post, processing, errors, hasErrors, wasSuccessful, reset} = useForm({
        name: "",
        url: "",
        helper_text: "",
        country_code: [] as string[],
        partner_id: 0,
        topic_id: 0,
    });

    useEffect(() => {
        if (wasSuccessful) {
            reset();
        }
    }, [wasSuccessful, reset]);

    useEffect(() => {
        setData('partner_id', partner.id);
    }, [partner]);


    useEffect(() => {
        if (hasErrors) {
            console.log(errors);
        }
    }, [hasErrors]);

    return (
        <Paper sx={{padding: 2, display: 'flex', flexDirection: 'column', gap: 2}}>
            <TextField
                fullWidth
                size={'small'}
                label="Name"
                value={data.name}
                error={!!errors.name}
                helperText={errors.name}
                onChange={e => setData('name', e.target.value)}
            />
            {topics && (
                <FormControl fullWidth variant={'outlined'}>
                    <InputLabel id={"topic-list"}>Topic</InputLabel>

                    <Select
                        labelId={"topic-list"}
                        label={'Topic'}
                        fullWidth
                        required
                        value={data.topic_id}
                        size={'small'}
                        error={!!errors.topic_id}
                        onChange={e => setData('topic_id', e.target.value as number)}
                    >
                        <MenuItem value={0}>
                            <em>You need to choose</em>
                        </MenuItem>
                        {topics.map(topic => (
                            <MenuItem key={'topic' + topic.id} value={topic.id}>
                                {topic.name}
                            </MenuItem>
                        ))}
                    </Select>
                </FormControl>
            )}
            <SelectCountries
                required
                countries={countries}
                defaultValue={data.country_code}
                onChangeSelect={(value: string[]) => setData('country_code', value)}
                error={!!errors.country_code}
            />
            <FormControl fullWidth variant={'outlined'}>
                <TextField
                    fullWidth
                    size={'small'}
                    label="URL"
                    value={data.url}
                    error={!!errors.url}
                    helperText={errors.url}
                    onChange={e => setData('url', e.target.value)}
                />
                <FormHelperText variant={'standard'} sx={{
                    display: 'flex',
                    flexDirection: 'column',
                    gap: 1
                }}>
                    <Typography variant={'caption'}>{"{short_link}"} = Identification for tracker</Typography>
                    <Typography variant={'caption'}>{"{uniq_user_hash}"} = Uniq user click</Typography>
                </FormHelperText>
            </FormControl>

            <TextField
                fullWidth
                size={'small'}
                label="Helper Text"
                value={data.helper_text}
                error={!!errors.helper_text}
                helperText={errors.helper_text}
                onChange={e => setData('helper_text', e.target.value)}
            />
            <Box sx={{display: 'flex', justifyContent: 'flex-end', gap: 2}}>
                <Button variant='contained' size='small' onClick={() => post(route('partner-links.store'))}
                        loading={processing}
                >
                    Add
                </Button>
            </Box>
        </Paper>
    );
}
