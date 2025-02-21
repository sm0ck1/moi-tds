import Typography from "@mui/material/Typography";
import Paper from "@mui/material/Paper";
import Grid from "@mui/material/Grid2";
import DashboardLayout from "@/Components/shared/DashboardLayout";
import {Portal} from "@/types/portal";
import {PageProps} from "@/types";
import Box from "@mui/material/Box";
import PortalPartnerLinks from "@/Pages/Portal/Partials/PortalPartnerLinks";
import {CountriesDict} from "@/types/country";

export default function PortalShow(
    {portal, partnerLinks, countries, landings}:
    PageProps<{ portal: Portal, partnerLinks: PartnerLink[], countries: CountriesDict, landings: GroupedLandings }>,
) {
    return (
        <DashboardLayout header={{
            title: 'Portal',
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
                        gap: 2,
                        display: 'flex',
                        flexDirection: 'column',
                    }}>
                        <Typography variant="h6" gutterBottom>
                            {portal.name}
                        </Typography>
                        <Typography variant="body1" gutterBottom>
                            {portal.short_url}
                        </Typography>
                        <Typography variant="body1" gutterBottom>
                            {portal.bot_url}
                        </Typography>
                        <Typography variant="body2" gutterBottom>
                            {portal.note}
                        </Typography>
                        <Typography variant="body2" gutterBottom>
                            {portal.topic?.name}
                        </Typography>
                        <Typography variant="body2" gutterBottom sx={{display: 'flex', gap: 2, justifyContent: 'end'}}>
                            <Box>
                                Created: {portal.created_at}
                            </Box>
                            <Box>
                                Updated: {portal.updated_at}
                            </Box>
                        </Typography>
                    </Paper>
                </Grid>
            </Grid>
        </DashboardLayout>
    )
}
