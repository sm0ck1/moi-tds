import {
    forwardRef,
    useEffect,
    useImperativeHandle,
    useRef,
} from 'react';
import TextField, {TextFieldProps} from "@mui/material/TextField";

export default forwardRef(function TextInput(
    {
        className = '',
        isFocused = false,
        textError = '',
        ...props
    }: TextFieldProps & { isFocused?: boolean, textError?: string },
    ref,
) {
    const localRef = useRef<HTMLInputElement>(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, [isFocused]);

    return (
        <TextField
            inputRef={localRef}
            error={!!textError}
            helperText={textError}
            size={'small'}
            {...props}
        />
    );
});
