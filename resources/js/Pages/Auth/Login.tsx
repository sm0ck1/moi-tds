import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/ui/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import {Head, Link, useForm} from '@inertiajs/react';
import {FormEventHandler} from 'react';
import Checkbox from "@mui/material/Checkbox";
import Button from "@mui/material/Button";
import Box from "@mui/material/Box";

export default function Login({
                                  status,
                                  canResetPassword,
                              }: {
    status?: string;
    canResetPassword: boolean;
}) {
    const {data, setData, post, processing, errors, reset} = useForm({
        email: '',
        password: '',
        remember: false as boolean,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in"/>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <Box component="form" noValidate sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                width: '100%',
                '& > :not(style)': {m: 1, width: '100%'}
            }} onSubmit={submit}>
                <TextInput
                    id="email"
                    type="email"
                    name="email"
                    fullWidth
                    label={'Email'}
                    value={data.email}
                    autoComplete="username"
                    textError={errors.email}
                    isFocused={true}
                    onChange={(e) => setData('email', e.target.value)}
                />


                <TextInput
                    id="password"
                    type="password"
                    name="password"
                    fullWidth
                    label={'Password'}
                    value={data.password}
                    autoComplete="current-password"
                    textError={errors.password}
                    onChange={(e) => setData('password', e.target.value)}
                />

                <div className="mt-4 block">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData(
                                    'remember',
                                    (e.target.checked || false) as false,
                                )
                            }
                        />
                        <span className="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                </div>

                <Box sx={{display: 'flex',
                    alignItems: {xs: 'flex-start', sm: 'center'},
                    flexDirection: {xs: 'column', sm: 'row'},
                    gap: 2,
                    width: '100%',
                    justifyContent: 'space-between'
                }}>

                    <Button disabled={processing} type="submit" variant={'contained'} color={'primary'}>
                        Log in
                    </Button>
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Forgot your password?
                        </Link>
                    )}
                </Box>
            </Box>
        </GuestLayout>
    );
}
