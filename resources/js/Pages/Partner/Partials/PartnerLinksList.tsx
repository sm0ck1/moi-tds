import {Box, FormControl, InputLabel, MenuItem, Select} from "@mui/material";
import Paper from "@mui/material/Paper";
import Grid from "@mui/material/Grid2";
import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import {useForm} from "@inertiajs/react";
import React, {useEffect, useState} from "react";
import {Topic} from "@/types/topic";
import Typography from "@mui/material/Typography";

export default function PartnerLinksList({partnerLinks, topics}: {
    partnerLinks: PartnerLinks[],
    topics: Topic[],
}) {
    const [editMode, setEditMode] = useState(0);
    const {data, setData, patch, processing, errors, wasSuccessful, reset} = useForm({
        name: "",
        url: "",
        topic_id: 0,
        helper_text: "",
        partner_id: 0,
    });

    useEffect(() => {
        if (wasSuccessful) {
            reset();
            setEditMode(0);
        }
    }, [wasSuccessful, reset]);

    return (
        <Grid container spacing={2}>
            {partnerLinks && partnerLinks.map((link) => (
                <Grid size={{xs: 12}} key={link.id}>
                    <Paper>
                        <Box sx={{padding: 2, display: 'flex', justifyContent: 'space-between', alignItems: 'center'}}>
                            <Box sx={{display: 'flex', gap: 2, alignItems: 'center'}}>
                                {editMode !== link.id && (
                                    <Button variant='contained' size='small' onClick={() => {
                                        setEditMode(link.id)
                                        setData('name', link.name)
                                        setData('url', link.url)
                                        setData('helper_text', link.helper_text)
                                        setData('partner_id', link.partner_id)
                                        setData('topic_id', link.topic_id)
                                    }}>Edit</Button>
                                )}
                                <Typography variant={'h6'}>{link.topic.name}</Typography>
                                <Typography variant={'body1'}>{link.name}</Typography>
                            </Box>
                            <Typography variant={'body2'}>{link.url}</Typography>
                        </Box>
                    </Paper>
                    {editMode === link.id && (<Paper sx={{padding: 2, display: 'flex', flexDirection: 'column', gap: 2}}>
                        <TextField
                            fullWidth
                            size={'small'}
                            label="Name"
                            value={editMode === link.id ? data.name : link.name}
                            disabled={editMode !== link.id}
                            error={editMode === link.id && !!errors.name}
                            helperText={editMode === link.id && errors.name}
                            onChange={e => setData('name', e.target.value)}
                        />
                        {topics && (
                            <FormControl fullWidth variant={'outlined'}>
                                <InputLabel id={"topic-list-" + link.id}>Topic</InputLabel>

                                <Select
                                    labelId={"topic-list-" + link.id}
                                    label={'Topic'}
                                    fullWidth
                                    required
                                    value={editMode === link.id ? data.topic_id : link.topic_id}
                                    disabled={editMode !== link.id}
                                    size={'small'}
                                    error={editMode === link.id && !!errors.topic_id}
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
                        <TextField
                            fullWidth
                            size={'small'}
                            label="URL"
                            value={editMode === link.id ? data.url : link.url}
                            error={editMode === link.id && !!errors.url}
                            helperText={editMode === link.id && errors.url}
                            disabled={editMode !== link.id}
                            onChange={e => setData('url', e.target.value)}
                        />
                        <TextField
                            fullWidth
                            size={'small'}
                            label="Helper Text"
                            value={editMode === link.id ? data.helper_text : link.helper_text}
                            disabled={editMode !== link.id}
                            error={editMode === link.id && !!errors.helper_text}
                            helperText={editMode === link.id && errors.helper_text}
                            onChange={e => setData('helper_text', e.target.value)}
                        />
                        <Box sx={{display: 'flex', justifyContent: 'flex-end', gap: 2}}>
                            {editMode === link.id && (
                                <>
                                    <Button variant='contained' size='small' onClick={() => {
                                        patch(route('partner-links.update', link.id))

                                    }}
                                            loading={processing}
                                    >Save</Button>
                                    <Button variant='contained' size='small' onClick={() => {
                                        setEditMode(0)
                                        setData('name', '')
                                        setData('url', '')
                                        setData('helper_text', '')
                                        setData('topic_id', 0)
                                        setData('partner_id', 0)
                                    }}>Cancel</Button>
                                </>
                            )}
                            {editMode !== link.id && (
                                <>
                                    <Button variant='contained' size='small' onClick={() => {
                                        setEditMode(link.id)
                                        setData('name', link.name)
                                        setData('url', link.url)
                                        setData('helper_text', link.helper_text)
                                        setData('partner_id', link.partner_id)
                                        setData('topic_id', link.topic_id)
                                    }}>Edit</Button>
                                    <Button variant='contained' size='small'>Delete</Button>
                                </>
                            )}

                        </Box>
                    </Paper>)}
                </Grid>
            ))}
        </Grid>
    );
}
