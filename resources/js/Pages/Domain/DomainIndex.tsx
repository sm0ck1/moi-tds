import DashboardLayout from "@/Components/shared/DashboardLayout";
import Grid from "@mui/material/Grid2";
import Paper from "@mui/material/Paper";
import Typography from "@mui/material/Typography";

export default function DomainIndex() {

    return (
        <DashboardLayout header={{
            title: 'Domains',
            buttons: [
                {
                    type: 'link',
                    href: route('domain.create'),
                    label: 'Add Domain',
                }
            ],
        }}>
            <Grid container spacing={2}>
                <Grid size={{xs: 12}}>
                    <Paper>
                        <Typography variant="h6" p={2}>
                            Domains
                        </Typography>
                    </Paper>
                </Grid>
            </Grid>
        </DashboardLayout>
    )
}
