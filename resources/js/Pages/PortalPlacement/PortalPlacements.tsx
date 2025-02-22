import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PortalPlacement} from "@/types/portalPlacement";
import {PageProps} from "@/types";
import {PaginationInterface} from "@/types/pagination";
import PortalPlacementIndex from "@/Pages/PortalPlacement/Partials/PortalPlacementIndex";
import Grid from "@mui/material/Grid2";
import PortalPlacementCounters from "@/Pages/PortalPlacement/Partials/PortalPlacementCounters";

type PortalPlacementsProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
    inSearch: number;
    waitingForPing: number;
    pinged: number;
    getToPing: number;
};

export default function PortalPlacements({portalPlacements, inSearch, waitingForPing, pinged, getToPing}: PageProps<PortalPlacementsProps>) {
    return (
        <DashboardLayout header={{
            title: 'Placements',
            buttons: [{
                type: 'link',
                href: route('portal-placements.create'),
                label: 'Add Placements',
            }],
        }}>
            <Grid container spacing={2}>
                <Grid size={12}>
                    <PortalPlacementCounters getToPing={getToPing} pinged={pinged} inSearch={inSearch} waitingForPing={waitingForPing}/>
                </Grid>
                <Grid size={12}>
                    <PortalPlacementIndex portalPlacements={portalPlacements}/>
                </Grid>
            </Grid>
        </DashboardLayout>
    );
}
