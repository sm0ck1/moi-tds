import {useForm} from "@inertiajs/react";
import React, {useEffect} from "react";
import {PageProps} from "@/types";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import TextInput from "@/Components/ui/TextInput";
import AppBar from "@mui/material/AppBar";
import Button from "@mui/material/Button";
import {Topic} from "@/types/topic";

export default function TopicCreate({topic} : PageProps<{topic: Topic}>) {

    const {data, setData, patch, processing, errors} = useForm({
        name: "",
        slug: "",
        description: "",
    })

    useEffect(() => {
        setData('name', topic.name)
        setData('slug', topic.slug)
        setData('description', topic.description)

    }, [topic])

    function submit(e: React.FormEvent) {
        e.preventDefault()
        patch(route('topic.update', topic.id))
    }

    return (
        <DashboardLayout header={{
            title: 'Create Topic',
            buttons: [
                {
                    type: 'link',
                    href: route('topic.index'),
                    label: 'Back to Topics',
                },
                {
                    type: 'link',
                    href: route('topic.create'),
                    label: 'Add Topic',
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
                        <TextInput
                            required
                            id="topic-slug"
                            label="Slug"
                            name="url"
                            onChange={e => setData('slug', e.target.value)}
                            value={data.slug}
                            fullWidth
                            textError={errors.slug}
                        />
                        <TextInput
                            id="topic-description"
                            label="Description"
                            name="description"
                            onChange={e => setData('description', e.target.value)}
                            value={data.description}
                            fullWidth
                            textError={errors.description}
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
