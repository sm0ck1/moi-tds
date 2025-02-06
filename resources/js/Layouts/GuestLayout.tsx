import { PropsWithChildren } from 'react';
import Box from "@mui/material/Box";
import Paper from "@mui/material/Paper";

export default function Guest({ children }: PropsWithChildren) {
    return (
        <Box sx={{ display: 'flex', justifyContent: 'center', alignItems: 'center', minHeight: '100vh' }}>

            <Paper sx={{ padding: 2, width: '100%', maxWidth: 400 }}>
                {children}
            </Paper>
        </Box>
    );
}
