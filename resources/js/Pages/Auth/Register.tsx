
import TextInput from '@/Components/ui/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import Button from "@mui/material/Button";
import Box from "@mui/material/Box";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <Box component="form" noValidate sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                width: '100%',
                '& > :not(style)': {m: 1, width: '100%'}
            }} onSubmit={submit}>
                <div>

                    <TextInput
                        id="name"
                        label={'Name'}
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full"
                        autoComplete="name"
                        isFocused={true}
                        fullWidth
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />

                </div>

                <div className="mt-4">

                    <TextInput
                        fullWidth
                        id="email"
                        label={'Email'}
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />

                </div>

                <div className="mt-4">

                    <TextInput
                        fullWidth
                        id="password"
                        label={'Password'}
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />

                </div>

                <div className="mt-4">

                    <TextInput
                        fullWidth
                        id="password_confirmation"
                        label={'Password confirmation'}
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        required
                    />

                </div>

                <Box sx={{display: 'flex',
                    alignItems: {xs: 'flex-start', sm: 'center'},
                    flexDirection: {xs: 'column', sm: 'row'},
                    gap: 2,
                    width: '100%',
                    justifyContent: 'space-between'
                }}>
                    <Link
                        href={route('login')}
                        className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Already registered?
                    </Link>

                    <Button variant='contained' type={'submit'} disabled={processing}>
                        Register
                    </Button>
                </Box>
            </Box>
        </GuestLayout>
    );
}
