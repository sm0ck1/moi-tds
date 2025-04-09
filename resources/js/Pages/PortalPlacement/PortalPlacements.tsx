import DashboardLayout from "@/Components/shared/DashboardLayout";
import {PortalPlacement} from "@/types/portalPlacement";
import {PageProps} from "@/types";
import {PaginationInterface} from "@/types/pagination";
import PortalPlacementIndex from "@/Pages/PortalPlacement/Partials/PortalPlacementIndex";
import Grid from "@mui/material/Grid2";
import PortalPlacementCounters from "@/Pages/PortalPlacement/Partials/PortalPlacementCounters";
import {objectToQueryString} from "@/services/objectToQueryString";
import {ButtonGroup} from "@mui/material";
import Button from "@mui/material/Button";
import {useForm} from "@inertiajs/react";
import React from "react";
import Box from "@mui/material/Box";

type PortalPlacementsProps = {
    portalPlacements: PaginationInterface<PortalPlacement>;
    inSearch: number;
    waitingForPing: number;
    pinged: number;
    getToPing: number;
    totalPortalPlacements: number;
};

export default function PortalPlacements({portalPlacements, inSearch, waitingForPing, pinged, getToPing, totalPortalPlacements}: PageProps<PortalPlacementsProps>) {
    const {post, processing, errors} = useForm({
        ping: 0,
    })

    const f1 = objectToQueryString({
        filter: {
            get_to_ping: 1,
            ping_counter: 0
        }
    })
    const currentLocation = window.location.href;

    const pingAgainHandler = async (e: React.FormEvent) => {
        e.preventDefault()
        post('portal-placements-ping-again' + window.location.search)
    }

    const pingIsOkHandler = async (e: React.FormEvent) => {
        e.preventDefault()
        post('portal-placements-ping-is-ok' + window.location.search)
    }

    return (
        <DashboardLayout header={{
            title: 'Placements',
            buttons: [{
                type: 'link',
                href: route('portal-placements.create'),
                label: 'Add Placements',
            }],
            filters: [
                {
                    type: 'link',
                    label: 'All',
                    href: route('portal-placements.index'),
                    current: route('portal-placements.index') === decodeURI(currentLocation),
                },
                {
                    type: "link",
                    label: 'Only get ping',
                    current: currentLocation.includes(f1),
                    href: route('portal-placements.index', {
                        'filter': {
                            get_to_ping: 1,
                            ping_counter: 0
                        }
                    }),
                }
            ],
            search: {
                paramName: 'filter[external_url]',
            }
        }}>
            <Grid container spacing={2}>
                <Grid size={12}>
                    <PortalPlacementCounters
                        totalPortalPlacements={totalPortalPlacements}
                        getToPing={getToPing}
                        pinged={pinged}
                        inSearch={inSearch}
                        waitingForPing={waitingForPing}
                    />
                </Grid>
                <Grid size={12}>
                    {currentLocation.includes('filter') && <Box sx={{
                        mb: 2
                    }}>
                        <ButtonGroup>
                            <Button onClick={pingAgainHandler}
                                disabled={processing}
                            >Ping again</Button>
                            <Button onClick={pingIsOkHandler}
                                    disabled={processing}
                            >Mark is OK</Button>
                        </ButtonGroup>
                    </Box>}
                    <PortalPlacementIndex portalPlacements={portalPlacements}/>
                </Grid>
            </Grid>
        </DashboardLayout>
    );
}
