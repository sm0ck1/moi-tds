import * as React from 'react';
import { Theme, useTheme } from '@mui/material/styles';
import Box from '@mui/material/Box';
import OutlinedInput from '@mui/material/OutlinedInput';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';
import FormControl from '@mui/material/FormControl';
import Select, {SelectChangeEvent, SelectProps} from '@mui/material/Select';
import Chip from '@mui/material/Chip';
import TextField from '@mui/material/TextField';

interface SelectLandingsProps {
    landings: GroupedLandings;
    defaultValue?: string[];
    onChangeSelect: (views: string[]) => void;
}

const ITEM_HEIGHT = 48;
const ITEM_PADDING_TOP = 8;
const MenuProps = {
    PaperProps: {
        style: {
            maxHeight: ITEM_HEIGHT * 4.5 + ITEM_PADDING_TOP,
            width: 300,
        },
    },
};

function getStyles(name: string, selectedViews: readonly string[], theme: Theme) {
    return {
        fontWeight: selectedViews.includes(name)
            ? theme.typography.fontWeightMedium
            : theme.typography.fontWeightRegular,
    };
}

export default function SelectLandings({ landings, defaultValue = [], onChangeSelect, ...props }: SelectLandingsProps & SelectProps) {
    const theme = useTheme();
    const [selectedViews, setSelectedViews] = React.useState<string[]>(defaultValue);
    const [searchText, setSearchText] = React.useState('');

    React.useEffect(() => {
        setSelectedViews(defaultValue);
    }, [defaultValue]);

    const handleChange = (event: SelectChangeEvent<typeof selectedViews>) => {
        const { target: { value } } = event;
        const newValue = typeof value === 'string' ? value.split(',') : value;

        // Проверяем, принадлежит ли изменяемое значение группе, которая уже выбрана целиком
        const groupViewsMap = new Map<string, string[]>();
        Object.entries(landings).forEach(([group, files]) => {
            const views = Object.values(files).map(landing => landing.view);
            groupViewsMap.set(group, views);
        });

        const isPartOfSelectedGroup = (view: string) => {
            for (const [group, views] of groupViewsMap) {
                if (views.includes(view) && views.every(v => selectedViews.includes(v))) {
                    return group;
                }
            }
            return null;
        };

        const filteredNewValue = newValue.filter(view => {
            const group = isPartOfSelectedGroup(view);
            // Если элемент принадлежит полностью выбранной группе, оставляем его только если он уже был выбран
            return !group || selectedViews.includes(view);
        });

        setSelectedViews(filteredNewValue);
        onChangeSelect(filteredNewValue);
    };

    const handleGroupClick = (groupName: string) => {

        const newSelectedViews = [...new Set([...selectedViews, groupName])];
        setSelectedViews(newSelectedViews);
        onChangeSelect(newSelectedViews);
    };

    const filteredLandings = Object.entries(landings)
        .map(([group, files]) => ({
            group,
            files: Object.entries(files).filter(([_, landing]) =>
                landing.view.toLowerCase().includes(searchText.toLowerCase())
            ),
        }))
        .filter(group => group.files.length > 0);

    return (
        <FormControl fullWidth size={'small'}>
            <InputLabel id="select-landings">Select Landing</InputLabel>
            <Select
                {...props}
                labelId="select-landings"
                id="select-landings-chip"
                multiple
                value={selectedViews}
                onChange={handleChange}
                input={<OutlinedInput id="select-multiple-chip" label="Select Landing" size={'small'} />}
                renderValue={(selected) => (
                    <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 0.5 }}>
                        {selected.map((value) => (
                            <Chip key={value} label={value} variant={'outlined'} size={'small'} />
                        ))}
                    </Box>
                )}
                MenuProps={MenuProps}
                onOpen={() => setSearchText('')}
            >
                <Box sx={{ p: 1, position: 'sticky', top: 0, bgcolor: 'background.paper', zIndex: 1 }}>
                    <TextField
                        size="small"
                        autoFocus
                        placeholder="Search landing..."
                        fullWidth
                        value={searchText}
                        onChange={(e) => setSearchText(e.target.value)}
                        onKeyDown={(e) => e.stopPropagation()}
                        onClick={(e) => e.stopPropagation()}
                    />
                </Box>
                {filteredLandings.map(({ group, files }) => {
                    const groupSelected = selectedViews.includes(group);
                    return [
                        <MenuItem
                            key={group}
                            value={group}
                            onClick={() => handleGroupClick(group)}
                            sx={{ fontWeight: 'bold', bgcolor: 'grey.100' }}
                        >
                            {group.toUpperCase()} {groupSelected && '(Selected)'}
                        </MenuItem>,
                        ...files.map(([file, landing]) => (
                            <MenuItem
                                key={landing.view}
                                value={landing.view}
                                style={getStyles(landing.view, selectedViews, theme)}
                                disabled={groupSelected} // Визуально отключаем
                            >
                                {landing.image && (
                                    <img
                                        src={landing.image}
                                        alt={file}
                                        style={{ width: 30, height: 20, marginRight: 10 }}
                                    />
                                )}
                                {landing.view}
                            </MenuItem>
                        )),
                    ];
                })}
            </Select>
        </FormControl>
    );
}
