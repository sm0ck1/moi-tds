import {defaultAnimateLayoutChanges, useSortable} from "@dnd-kit/sortable";
import {ListSubheader, MenuItem, Paper, Select, Typography} from "@mui/material";
import Box from "@mui/material/Box";
import DragIndicatorIcon from "@mui/icons-material/DragIndicator";
import IconButton from "@mui/material/IconButton";
import DeleteIcon from "@mui/icons-material/Delete";
import FormControlLabel from "@mui/material/FormControlLabel";
import Checkbox from "@mui/material/Checkbox";
import Divider from "@mui/material/Divider";
import TextInput from "@/Components/ui/TextInput";
import {CSS} from '@dnd-kit/utilities';
import React, {Fragment} from "react";
import _ from "lodash";
import Tooltip from "@mui/material/Tooltip";
import SelectCountries from "@/Components/ui/SelectCountries";
import {CountriesDict} from "@/types/country";

interface SortableFlowProps {
    flow: any;
    flowIndex: number;
    isFallback: boolean;
    onRemove: () => void;
    onPartnerLinkChange: (e: any) => void;
    onConditionOperatorChange: (value: 'in' | 'not') => void;
    onConditionValuesChange: (values: string[]) => void;
    onDeviceValueChange: (value: 'desktop' | 'mobile') => void;
    partnerLinks: any[];
    toggleCondition: (flowIndex: number, type: 'country' | 'device', enabled: boolean) => void;
    countries: CountriesDict;
}

export default function SortableFlow({
                                         flow,
                                         flowIndex,
                                         isFallback,
                                         onRemove,
                                         onPartnerLinkChange,
                                         onConditionOperatorChange,
                                         onConditionValuesChange,
                                         onDeviceValueChange,
                                         partnerLinks,
                                         toggleCondition,
                                         countries
                                     }: SortableFlowProps) {

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging
    } = useSortable({
        id: flowIndex.toString(),
        disabled: isFallback
    });

    const style = transform ? {
        transform: CSS.Transform.toString(transform),
        transition,
    } : undefined;
    return (
        <Paper
            style={style}
            ref={setNodeRef}
            sx={{
                display: 'flex',
                flexDirection: 'column',
                gap: 2,
                mt: 2,
                padding: 2,
                width: '100%',
                backgroundColor: isFallback ? '#f5f5f5' : isDragging ? '#e3f2fd' : 'white',
                cursor: isFallback ? 'default' : 'grab',
            }}
        >
            <Box sx={{
                display: 'flex',
                gap: 2,
                pb: 1,
                alignItems: 'center',
                width: '100%',
                borderBottom: '1px solid #e0e0e0',
            }}>
                {!isFallback && (
                    <div {...attributes} {...listeners}>
                        <DragIndicatorIcon/>
                    </div>
                )}
                <Typography variant="h6" sx={{flex: 1}}>
                    {isFallback ? 'Fallback Flow' : `Flow ${flowIndex + 1}`}
                </Typography>
                <Box sx={{display: 'flex', gap: 2, alignItems: 'center'}}>
                    <Select
                        value={flow.partner_link_id}
                        onChange={onPartnerLinkChange}
                        variant={'outlined'}
                        size={'small'}
                    >
                        {partnerLinks && partnerLinks.length > 0 && Object.entries(_.groupBy(partnerLinks, 'partner.name')).map(([partnerName, links]: [string, PartnerLink[]]) => [
                            <ListSubheader key={`header-${partnerName}`}>
                                {partnerName}
                            </ListSubheader>,
                            ...links.map((partnerLink: PartnerLink) => (
                                <MenuItem
                                    key={partnerLink.id}
                                    value={partnerLink.id}
                                >
                                    <Box sx={{display: 'flex', flexDirection: 'column', pl: 2,}}>
                                        <Box sx={{display: 'flex', gap: 1, alignItems: 'center'}}>
                                            <Typography variant="body2" sx={{fontWeight: 500}}>
                                                {partnerLink.topic.name}: {partnerLink.name}
                                            </Typography>
                                            <Box sx={{display: 'flex', gap: 1}}>
                                                {partnerLink.countries.map((country) => (
                                                    <Tooltip key={country.code} title={country.name}>
                                                        {country.flag}
                                                    </Tooltip>
                                                ))}
                                            </Box>
                                        </Box>
                                        <Box>
                                            <Typography variant="caption" color="text.secondary">
                                                {partnerLink.url}
                                            </Typography>
                                        </Box>
                                    </Box>
                                </MenuItem>
                            ))
                        ]).flat()}
                    </Select>
                    {!isFallback && (
                        <IconButton onClick={onRemove}>
                            <DeleteIcon/>
                        </IconButton>
                    )}
                </Box>
            </Box>

            {!isFallback && (
                <Box sx={{width: '100%'}}>
                    <Box sx={{display: 'flex', gap: 2, py: 1}}>
                        <FormControlLabel
                            control={
                                <Checkbox
                                    checked={!!flow.conditions.country}
                                    onChange={(e) => toggleCondition(flowIndex, 'country', e.target.checked)}
                                />
                            }
                            label="Countries"
                            sx={{width: '100px'}}
                        />
                        {flow.conditions.country && (
                            <>
                                <Select
                                    size={'small'}
                                    value={flow.conditions.country.operator}
                                    onChange={(e) => onConditionOperatorChange(e.target.value as 'in' | 'not')}
                                    sx={{width: '230px'}}
                                >
                                    <MenuItem value='in'>IN</MenuItem>
                                    <MenuItem value='not'>NOT</MenuItem>
                                </Select>
                                <SelectCountries
                                    required
                                    countries={countries}
                                    defaultValue={flow.conditions.country.values}
                                    onChangeSelect={(value: string[]) => onConditionValuesChange(value)}
                                />
                            </>
                        )}
                    </Box>
                    <Divider/>
                    <Box sx={{display: 'flex', gap: 2, py: 1}}>
                        <FormControlLabel
                            control={
                                <Checkbox
                                    checked={!!flow.conditions.device}
                                    onChange={(e) => toggleCondition(flowIndex, 'device', e.target.checked)}
                                />
                            }
                            label="Device"
                            sx={{width: '100px'}}
                        />
                        {flow.conditions.device && (
                            <Select
                                size={'small'}
                                value={flow.conditions.device.value}
                                onChange={(e) => onDeviceValueChange(e.target.value as 'desktop' | 'mobile')}
                                sx={{width: '150px'}}
                            >
                                <MenuItem value='desktop'>Desktop</MenuItem>
                                <MenuItem value='mobile'>Mobile</MenuItem>
                            </Select>
                        )}
                    </Box>
                </Box>
            )}
        </Paper>
    );
}
