import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PortalPlacement} from "@/types/portalPlacement";
import {PageProps} from "@/types";
import {PaginationInterface} from "@/types/pagination";
import PortalPlacementIndex from "@/Pages/PortalPlacement/Partials/PortalPlacementIndex";

type PortalPlacementsProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
};

export default function PortalPlacements({portalPlacements}: PageProps<PortalPlacementsProps>) {
    return (
        <DashboardLayout header={{
            title: 'Placements',
            buttons: [{
                type: 'link',
                href: route('portal-placements.create'),
                label: 'Add Placements',
            }],
        }}>
            <PortalPlacementIndex portalPlacements={portalPlacements}/>
        </DashboardLayout>
    );
}
