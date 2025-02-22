import {Card, CardContent} from "@mui/material";
import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";

type PortalPlacementCountersProps = {
    inSearch: number;
    waitingForPing: number;
    pinged: number;
    getToPing: number;
}

export default function PortalPlacementCounters({
                                                    waitingForPing,
                                                    inSearch,
                                                    pinged,
                                                    getToPing
                                                }: PortalPlacementCountersProps) {
    return (
        <Box sx={{
            display: 'flex',
            flexDirection: 'row',
            gap: 2
        }}>
            <CardCounter title="In Search" counter={inSearch}/>

            <CardCounter title="Waiting for Ping" counter={waitingForPing}/>
            <CardCounter title="Get to Ping" counter={getToPing}/>
            <CardCounter title="Pinged" counter={pinged}/>
        </Box>
    )
}

function CardCounter({title, counter}: { title: string, counter: number }) {

    return (
        <Card sx={{
            width: '100%',
        }}>
            <CardContent>
                <Typography variant="h6" component="div" fontSize={14}>
                    {title}
                </Typography>
                <Typography variant="h5" component="div">
                    {counter}
                </Typography>
            </CardContent>
        </Card>
    );
}
