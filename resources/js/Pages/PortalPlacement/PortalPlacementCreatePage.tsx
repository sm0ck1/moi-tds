import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PortalPlacement} from "@/types/portalPlacement";
import {PageProps} from "@/types";
import {PaginationInterface} from "@/types/pagination";
import PortalPlacementCreate from "@/Pages/PortalPlacement/Partials/PortalPlacementCreate";
import {Portal} from "@/types/portal";

type PortalPlacementsProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
    portals: Portal[];
};

export default function PortalPlacements({portalPlacements, portals}: PageProps<PortalPlacementsProps>) {
    return (
        <DashboardLayout header={{
            title: 'Placement create',
            buttons: [{
                type: 'link',
                href: route('portal-placements.index'),
                label: 'List of placements',
            }],
        }}>
            <PortalPlacementCreate portals={portals} />
        </DashboardLayout>
    );
}
