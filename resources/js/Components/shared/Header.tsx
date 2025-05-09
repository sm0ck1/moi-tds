import * as React from 'react';
import AppBar from '@mui/material/AppBar';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import Grid from '@mui/material/Grid2';
import IconButton from '@mui/material/IconButton';
import {Head, Link} from '@inertiajs/react';
import MenuIcon from '@mui/icons-material/Menu';
import NotificationsIcon from '@mui/icons-material/Notifications';
import Toolbar from '@mui/material/Toolbar';
import Tooltip from '@mui/material/Tooltip';
import Typography from '@mui/material/Typography';
import {ButtonGroup} from "@mui/material";
import SearchBar, {SearchBarProps} from "@/Components/ui/SearchBar";

interface HeaderProps {
    onDrawerToggle: () => void;
    title?: string;
    buttons?: { type: 'link' | 'button', href: string, label: string, current?: boolean, handleClick?: ()=>void  }[];
    filters?: { type: 'link' | 'button', href: string, label: string, current?: boolean, handleClick?: ()=>void }[];
    search?: SearchBarProps;
}

export default function Header({onDrawerToggle, buttons, filters, title, search}: HeaderProps) {



    return (
        <>
            <Head title={title}/>
            <AppBar color="primary" position="sticky" elevation={0}>
                <Toolbar>
                    <Grid container spacing={1}
                          sx={{alignItems: 'center', justifyContent: 'space-between', width: '100%'}}>
                        <Grid sx={{display: {sm: 'none', xs: 'block'}}}>
                            <IconButton
                                color="inherit"
                                aria-label="open drawer"
                                onClick={onDrawerToggle}
                                edge="start"
                            >
                                <MenuIcon/>
                            </IconButton>
                        </Grid>
                        <Typography color="inherit" variant="h5" component="h1">
                            {title || 'Title'}
                        </Typography>
                        <Grid>
                            <Tooltip title="Alerts • No alerts">
                                <IconButton color="inherit">
                                    <NotificationsIcon/>
                                </IconButton>
                            </Tooltip>
                            <IconButton color="inherit" sx={{p: 0.5}}>
                                <Avatar src="/static/images/avatar/1.jpg" alt="My Avatar"/>
                            </IconButton>
                        </Grid>
                    </Grid>
                </Toolbar>
            </AppBar>
            {(buttons || filters) && (
                <AppBar
                    component="div"
                    color="primary"
                    position="sticky"
                    elevation={1}
                >
                    <Toolbar>
                        <Grid container spacing={1}
                              sx={{alignItems: 'center', width: '100%'}}>
                            {buttons && (
                                <Grid>
                                    <Grid container>
                                        {buttons.map((button, index) => (
                                            <Grid key={index}>
                                                {button.type === 'link' ? (
                                                    <Button
                                                        color="info"
                                                        variant="contained"
                                                        component={Link as React.ElementType}
                                                        href={button.href}
                                                    >
                                                        {button.label}
                                                    </Button>
                                                ) : (
                                                    <Button
                                                        color="inherit"
                                                        variant="outlined"
                                                        onClick={button.handleClick}
                                                    >
                                                        {button.label}
                                                    </Button>
                                                )}
                                            </Grid>
                                        ))}
                                    </Grid>
                                </Grid>
                            )}
                            {filters && (
                                <Grid component={ButtonGroup}>
                                    {filters.map((button, index) => (
                                        <Grid key={index}>
                                            {button.type === 'link' ? (
                                                <Button
                                                    color="info"
                                                    variant="contained"
                                                    component={Link as React.ElementType}
                                                    href={button.href}
                                                    disabled={window.location.href === button.href || button.current}
                                                >
                                                    {button.label}
                                                </Button>
                                            ) : (
                                                <Button
                                                    sx={{
                                                        '&:disabled': {
                                                            color: button.current ? '#555' : 'inherit',
                                                            borderColor: button.current ? '#f5f5f5' : 'inherit',
                                                            backgroundColor: button.current ? '#f5f5f5' : 'inherit',
                                                        }
                                                    }}
                                                    color="info"
                                                    variant="contained"
                                                    onClick={button.handleClick}
                                                    disabled={button.current}
                                                >
                                                    {button.label}
                                                </Button>
                                            )}
                                        </Grid>
                                    ))}
                                </Grid>
                            )}
                            {search &&
                                <SearchBar {...search} />
                            }
                        </Grid>
                    </Toolbar>
                </AppBar>
            )}
        </>
    )
        ;
}
