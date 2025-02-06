import {PageProps} from "@/types";
import {Domain} from "node:domain";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import Typography from "@mui/material/Typography";

export default function DomainEdit({domain}: PageProps<{domain: Domain}>) {
    return (
        <DashboardLayout header={{
            title: 'Edit Domain',
            buttons: [
                {
                    type: 'link',
                    href: route('domain.index'),
                    label: 'Back',
                }
            ],
        }}>
            <Grid container spacing={2}>
                <Grid size={{xs: 12}}>
                    <Paper>
                        <Typography variant="h6" p={2}>
                            Edit Domain
                        </Typography>
                    </Paper>
                </Grid>
            </Grid>
        </DashboardLayout>
    )
}
