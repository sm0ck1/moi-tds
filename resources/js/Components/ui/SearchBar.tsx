import React, { useState, useEffect } from 'react';
import {
    TextField,
    InputAdornment,
    IconButton,
    Box
} from '@mui/material';
import SearchIcon from '@mui/icons-material/Search';
import ClearIcon from '@mui/icons-material/Clear';
import { router } from '@inertiajs/react';

export interface SearchBarProps {
    /**
     * The URL parameter name to use
     * Example: 'external_url'
     */
    paramName: string;

    /**
     * Placeholder text for the search field
     */
    placeholder?: string;

    /**
     * Delay in milliseconds before triggering search after typing
     */
    debounceTime?: number;

    /**
     * Whether to replace URL history entry or push a new one
     */
    replace?: boolean;
}

/**
 * Simple search component that updates URL parameters directly
 */
const SearchBar: React.FC<SearchBarProps> = ({
                                                 paramName = 'external_url',
                                                 placeholder = 'Search...',
                                                 debounceTime = 300,
                                                 replace = true
                                             }) => {
    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);

    const initialValue = urlParams.get(paramName) || '';

    const [searchValue, setSearchValue] = useState<string>(initialValue);

    // Debounce the search input
    useEffect(() => {
        const handler = setTimeout(() => {
            // Only update if value changed
            if (searchValue !== initialValue) {
                updateUrl(searchValue);
            }
        }, debounceTime);

        return () => {
            clearTimeout(handler);
        };
    }, [searchValue, debounceTime]);

    const updateUrl = (value: string) => {
        // Get current URL parameters
        const currentParams = new URLSearchParams(window.location.search);

        // Update or remove the search parameter
        if (value && value.trim() !== '') {
            currentParams.set(paramName, value);
        } else {
            currentParams.delete(paramName);
        }

        // Build the new URL with updated parameters
        const newUrl = window.location.pathname +
            (currentParams.toString() ? '?' + currentParams.toString() : '');

        // Navigate using Inertia
        router.visit(newUrl, {
            replace: replace,
            preserveScroll: true, // Keep scroll position
            preserveState: true   // Keep component state
        });
    };

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setSearchValue(event.target.value);
    };

    const handleClear = () => {
        setSearchValue('');
        updateUrl('');
    };

    // Handle form submission
    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault();
        updateUrl(searchValue);
    };

    return (
        <Box sx={{ width: '100%', maxWidth: 500 }}>
            <form onSubmit={handleSubmit}>
                <TextField
                    fullWidth
                    value={searchValue}
                    onChange={handleChange}
                    placeholder={placeholder}
                    variant="outlined"
                    size="small"
                    InputProps={{
                        startAdornment: (
                            <InputAdornment position="start">
                                <SearchIcon />
                            </InputAdornment>
                        ),
                        endAdornment: searchValue ? (
                            <InputAdornment position="end">
                                <IconButton
                                    aria-label="clear search"
                                    onClick={handleClear}
                                    edge="end"
                                    size="small"
                                >
                                    <ClearIcon />
                                </IconButton>
                            </InputAdornment>
                        ) : null
                    }}
                />
            </form>
        </Box>
    );
};

export default SearchBar;
