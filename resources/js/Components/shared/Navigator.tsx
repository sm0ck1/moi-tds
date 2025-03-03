import * as React from 'react';
import Divider from '@mui/material/Divider';
import Drawer, {DrawerProps} from '@mui/material/Drawer';
import List from '@mui/material/List';
import Box from '@mui/material/Box';
import ListItem from '@mui/material/ListItem';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemIcon from '@mui/material/ListItemIcon';
import ListItemText from '@mui/material/ListItemText';
import HomeIcon from '@mui/icons-material/Home';
import PeopleIcon from '@mui/icons-material/People';
import DnsRoundedIcon from '@mui/icons-material/DnsRounded';
import DatasetLinkedIcon from '@mui/icons-material/DatasetLinked';
import {Link} from '@inertiajs/react';


const item = {
    py: '2px',
    px: 3,
    color: 'rgba(255, 255, 255, 0.7)',
    '&:hover, &:focus': {
        bgcolor: 'rgba(255, 255, 255, 0.08)',
    },
};

const itemCategory = {
    boxShadow: '0 -1px 0 rgb(255,255,255,0.1) inset',
    py: 1.5,
    px: 3,
};

export default function Navigator(props: DrawerProps) {
    const categories = [
        {
            id: 'TDS',
            children: [
                {
                    id: 'Dashboard',
                    icon: <HomeIcon/>,
                    link: route('dashboard'),
                    active: route().current('dashboard'),
                },
                {
                    id: 'Visits',
                    icon: <PeopleIcon/>,
                    link: route('visits.index'),
                    active: route().current('visits.*'),
                },
                {
                    id: 'Partners',
                    icon: <PeopleIcon/>,
                    link: route('partners.index'),
                    active: route().current('partners.*'),
                },
                {
                    id: 'Portals',
                    icon: <DatasetLinkedIcon/>,
                    active: route().current('portal.*'),
                    link: route('portal.index'),
                },
                {
                    id: 'Placements',
                    icon: <DatasetLinkedIcon/>,
                    active: route().current('portal-placements.*'),
                    link: route('portal-placements.index'),
                },
            ],
        },
        {
            id: 'Setting',
            children: [
                {
                    id: 'Topics',
                    icon: <DnsRoundedIcon/>,
                    link: route('topic.index'),
                    active: route().current('topic.*'),
                },
                {
                    id: 'Domains',
                    icon: <DnsRoundedIcon/>,
                    link: route('domain.index'),
                    active: route().current('domain.*'),
                },
            ],
        },
    ];

    const {...other} = props;
    return (
        <Drawer variant="permanent" {...other}>
            <List disablePadding>
                <ListItem sx={{...item, ...itemCategory, fontSize: 22, color: '#fff'}}>
                    MOI TDS
                </ListItem>

                {categories.map(({id, children}) => (
                    <Box key={id} sx={{bgcolor: '#101F33'}}>
                        <ListItem sx={{py: 2, px: 3}}>
                            <ListItemText sx={{color: '#fff'}}>{id}</ListItemText>
                        </ListItem>
                        {children.map(({id: childId, icon, active, link}) => (
                            <ListItem disablePadding key={childId}>
                                <ListItemButton selected={active} sx={item} component={Link as React.ElementType}
                                                href={link} replace>
                                    <ListItemIcon>{icon}</ListItemIcon>
                                    <ListItemText>{childId}</ListItemText>
                                </ListItemButton>
                            </ListItem>
                        ))}
                        <Divider sx={{mt: 2}}/>
                    </Box>
                ))}
            </List>
        </Drawer>
    );
}
