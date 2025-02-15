import * as React from 'react';
import { Theme, useTheme } from '@mui/material/styles';
import Box from '@mui/material/Box';
import OutlinedInput from '@mui/material/OutlinedInput';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';
import FormControl from '@mui/material/FormControl';
import Select, { SelectChangeEvent, SelectProps } from '@mui/material/Select';
import Chip from '@mui/material/Chip';
import TextField from '@mui/material/TextField';
import {CountriesDict} from "@/types/country";

const ITEM_HEIGHT = 48;
const ITEM_PADDING_TOP = 8;
const MenuProps = {
    PaperProps: {
        style: {
            maxHeight: ITEM_HEIGHT * 4.5 + ITEM_PADDING_TOP,
            width: 250,
        },
    },
};

function getStyles(name: string, selectedCode: readonly string[], theme: Theme) {
    return {
        fontWeight: selectedCode.includes(name)
            ? theme.typography.fontWeightMedium
            : theme.typography.fontWeightRegular,
    };
}

interface SelectCountriesProps {
    countries: CountriesDict;
    defaultValue?: string[];
    onChangeSelect: (codes: string[]) => void;
}

export default function SelectCountries({countries, defaultValue = [], onChangeSelect, ...props}:SelectCountriesProps & SelectProps) {
    const theme = useTheme();
    const [selectedCode, setSelectedCode] = React.useState<string[]>(defaultValue);
    const [searchText, setSearchText] = React.useState('');

    React.useEffect(() => {
        setSelectedCode(defaultValue);
    }, [defaultValue]);

    const handleChange = (event: SelectChangeEvent<typeof selectedCode>) => {
        const {
            target: { value },
        } = event;

        const newValue = typeof value === 'string' ? value.split(',') : value;

        let updatedValue: string[];

        // Проверяем, было ли значение ALL удалено из выбранных
        const wasAllRemoved = selectedCode.includes('ALL') && !newValue.includes('ALL');

        if (wasAllRemoved) {
            updatedValue = [];
        }
        // Если выбран ALL
        else if (newValue.includes('ALL') && !selectedCode.includes('ALL')) {
            updatedValue = ['ALL'];
        }
        // Если уже был выбран ALL и выбирается другая страна
        else if (selectedCode.includes('ALL')) {
            updatedValue = [newValue[newValue.length - 1]];
        }
        else {
            updatedValue = newValue;
        }

        setSelectedCode(updatedValue);
        onChangeSelect(updatedValue);
    };

    const filteredCountries = Object.entries(countries).filter(([code, country]) =>
        country.name.toLowerCase().includes(searchText.toLowerCase()) ||
        code.toLowerCase().includes(searchText.toLowerCase())
    );

    return (
            <FormControl fullWidth size={'small'}>
                <InputLabel id="select-countries">Countries</InputLabel>
                <Select
                    {...props}
                    labelId="select-countries"
                    id="select-countries-chip"
                    multiple
                    value={selectedCode as string[]}
                    onChange={handleChange}
                    input={<OutlinedInput id="select-multiple-chip" label="Countries" size={'small'} />}
                    renderValue={(selected) => (
                        <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 0.5 }}>
                            {selected.map((value) => (
                                <Chip
                                    key={value}
                                    label={value === 'ALL' ? '🌎 ALL' : countries[value].flag + ' ' + countries[value].code}
                                    variant={'outlined'}
                                    size={'small'}
                                />
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
                            placeholder="Search country..."
                            fullWidth
                            value={searchText}
                            onChange={(e) => setSearchText(e.target.value)}
                            onClick={(e) => e.stopPropagation()}
                        />
                    </Box>
                    {filteredCountries.map(([code, country]) => (
                        <MenuItem
                            key={code}
                            value={code}
                            style={getStyles(code, selectedCode, theme)}
                            disabled={selectedCode.includes('ALL') && code !== 'ALL'}
                        >
                            {country.flag} {country.name}
                        </MenuItem>
                    ))}
                </Select>
            </FormControl>
    );
}
