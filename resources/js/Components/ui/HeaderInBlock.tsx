import Paper from "@mui/material/Paper";
import Typography from "@mui/material/Typography";

export default function HeaderInBlock({title}: { title: string }) {

    return (
        <Typography component={Paper} sx={{px: 2, py: 1}} variant={'h6'}>{title}</Typography>
    )
}
