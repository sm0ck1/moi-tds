import React from 'react';
import {MenuItem, Box, Typography, ListSubheader, Select} from '@mui/material';
import _ from 'lodash';

type GroupedPartnerLinksProps = {
    partnerLinks: PartnerLink[];
    currentChoose: number;
    handleChoice: (e: any) => void;
}

const GroupedPartnerLinks = ({partnerLinks, currentChoose, handleChoice}: GroupedPartnerLinksProps) => {
    // Group partner links by partner name
    const groupedLinks = _.groupBy(partnerLinks, 'partner.name');

    return (
        <Select
            value={currentChoose}
            onChange={handleChoice}
            variant={'outlined'}
            size={'small'}
        >
            {Object.entries(groupedLinks).map(([partnerName, links]: [partnerName: string, links: PartnerLink[]]) => (
                <Box key={partnerName}>
                    <ListSubheader>{partnerName}</ListSubheader>

                    {links.map((partnerLink: PartnerLink) => (
                        <MenuItem
                            key={partnerLink.id}
                            value={partnerLink.id}
                        >
                            <Box className="flex flex-col">
                                <Box>
                                    <Typography variant="body2" className="font-semibold">
                                        {partnerLink.topic.name}: {partnerLink.name}
                                    </Typography>
                                </Box>
                                <Box>
                                    <Typography variant="caption" className="text-gray-600">
                                        {partnerLink.url}
                                    </Typography>
                                </Box>
                            </Box>
                        </MenuItem>
                    ))}
                </Box>
            ))}
        </Select>
    );
};

export default GroupedPartnerLinks;
