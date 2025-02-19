import Paper from "@mui/material/Paper";
import {useForm} from "@inertiajs/react";
import {Portal} from "@/types/portal";
import FormControl from "@mui/material/FormControl/FormControl";
import InputLabel from "@mui/material/InputLabel/InputLabel";
import Select from "@mui/material/Select/Select";
import MenuItem from "@mui/material/MenuItem/MenuItem";
import Grid from "@mui/material/Grid2";
import HeaderInBlock from "@/Components/ui/HeaderInBlock";
import TextInput from "@/Components/ui/TextInput";
import React from "react";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";

type PortalPlacementCreateProps = {
    portals: Portal[];
}

export default function PortalPlacementCreate({portals}: PortalPlacementCreateProps) {

    const [previewLinks, setPreviewLinks] = React.useState<string[]>([])

    const {data, setData, post, processing, errors} = useForm({
        external_links: [] as string[],
        portal_id: 0,
    })

    React.useEffect(() => {
        setData('external_links', previewLinks)
    }, [previewLinks])

    function submit(e: React.FormEvent) {
        e.preventDefault()
        post(route('portal-placements.store'))
    }

    function convertLinks(links: string): void {
        const normalizedLinks = links
            .replace(/[\n;,\t\r]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

        let linkArray = normalizedLinks.split(' ');

        const result = linkArray
            .map(link => {
                link = link.trim()
                    .replace(/^["']+|["']+$/g, '') // Удаляем кавычки по краям
                    .replace(/[[\]<>]/g, ''); // Удаляем скобки и другие специальные символы

                try {
                    new URL(link);
                    return link;
                } catch {
                    if (link.match(/^[\w-]+(\.[\w-]+)+(\/[\w-?=&/.%]*)*$/)) {
                        return 'https://' + link;
                    }
                    return ''; // Возвращаем пустую строку для невалидных ссылок
                }
            })
            .filter((link, index, self) =>
                link.length > 0 && self.indexOf(link) === index
            );
        setPreviewLinks(result);
    }

    return (
        <Grid container spacing={2}>
            <Grid size={{xs: 12, md: 6}} sx={{display: 'flex', flexDirection: 'column', gap: 2, width: '100%'}}>
                <HeaderInBlock title={'Create'} />
                <Paper sx={{
                    padding: 2,
                    gap: 3,
                    display: 'flex',
                    flexDirection: 'column',
                    width: '100%',
                }}>
                    {portals && (
                        <FormControl fullWidth size={'small'}>
                            <InputLabel id="portals">Portal</InputLabel>
                            <Select
                                labelId="portals"
                                id="portal-select"
                                value={data.portal_id || 0}
                                label="Portal"
                                onChange={e => setData('portal_id', +e.target.value)}
                            >
                                <MenuItem value={0}>None</MenuItem>
                                {portals.map((portal) => (
                                    <MenuItem key={portal.id} value={portal.id}>{portal.name}</MenuItem>
                                ))}
                            </Select>
                        </FormControl>
                    )}
                    <TextInput
                        required
                        id="partner-note"
                        label="Note"
                        name="note"
                        multiline
                        rows={20}
                        onChange={e => convertLinks(e.target.value)}
                        fullWidth
                    />
                    <Button
                        type="submit"
                        variant="contained"
                        onClick={submit}
                        disabled={processing}
                    >
                        Save
                    </Button>
                </Paper>
            </Grid>
            <Grid size={{xs: 12, md: 6}} sx={{display: 'flex', flexDirection: 'column', gap: 2, width: '100%'}}>
                <HeaderInBlock title={'Preview ' + previewLinks.length + ' links'} />

                <Paper sx={{
                    padding: 2,
                    gap: 1,
                    fontSize: '11px',
                    display: 'flex',
                    flexDirection: 'column',
                    width: '100%',
                }}>
                    {previewLinks.map((link, index) => (
                        <Box key={index}>{link}</Box>
                    ))}
                </Paper>
            </Grid>
        </Grid>
    );
}
