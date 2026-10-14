import { Form, Head } from '@inertiajs/react';
import PasskeyVerify from '@/components/passkey-verify';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
};

const fieldClassName =
    'h-11 rounded-none border-[#262626] bg-[#141414] text-[#F5F5F0] placeholder:text-[#737373] focus-visible:border-[#F97316] focus-visible:ring-[#F97316]/40';
const labelClassName =
    'text-xs font-semibold tracking-[0.15em] text-[#A3A3A3] uppercase';
const buttonClassName =
    'h-12 w-full rounded-none bg-[#F97316] font-bold tracking-widest text-[#0B0B0B] uppercase shadow-none hover:bg-orange-400 focus-visible:border-[#F97316] focus-visible:ring-[#F97316]';

export default function Login({ status, canResetPassword }: Props) {
    return (
        <>
            <Head title="Member access" />

            <header className="mb-8 space-y-3">
                <p className="text-xs font-bold tracking-[0.2em] text-[#F97316] uppercase">
                    Member access
                </p>
                <h1 className="text-4xl leading-none font-black tracking-tight text-[#F5F5F0] uppercase">
                    Welcome back
                </h1>
                <p className="text-sm text-[#A3A3A3]">
                    Sign in to continue your training.
                </p>
            </header>

            {status && (
                <div
                    role="status"
                    className="mb-6 border border-emerald-900 bg-emerald-950/50 px-4 py-3 text-sm text-emerald-200"
                >
                    {status}
                </div>
            )}

            <PasskeyVerify buttonClassName="rounded-none border-[#262626] bg-[#141414] font-bold tracking-wide text-[#F5F5F0] uppercase shadow-none hover:bg-[#262626] hover:text-[#F5F5F0] focus-visible:border-[#F97316] focus-visible:ring-[#F97316]" />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor="email"
                                    className={labelClassName}
                                >
                                    Email
                                </Label>
                                <Input
                                    id="email"
                                    className={fieldClassName}
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="email"
                                    placeholder="name@example.com"
                                />
                                <InputError
                                    message={errors.email}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center justify-between gap-4">
                                    <Label
                                        htmlFor="password"
                                        className={labelClassName}
                                    >
                                        Password
                                    </Label>
                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="text-xs text-[#F97316] underline-offset-4 hover:underline"
                                            tabIndex={5}
                                        >
                                            Forgot password?
                                        </TextLink>
                                    )}
                                </div>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    required
                                    tabIndex={2}
                                    autoComplete="current-password"
                                    placeholder="Password"
                                    textToggle
                                    className={fieldClassName}
                                />
                                <InputError
                                    message={errors.password}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <label
                                htmlFor="remember"
                                className="flex items-center gap-3 text-sm text-[#A3A3A3]"
                            >
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    tabIndex={3}
                                    className="size-4 rounded-none border-[#525252] focus-visible:border-[#F97316] focus-visible:ring-[#F97316] data-[state=checked]:border-[#F97316] data-[state=checked]:bg-[#F97316] data-[state=checked]:text-[#0B0B0B]"
                                />
                                Remember me
                            </label>

                            <Button
                                type="submit"
                                className={buttonClassName}
                                tabIndex={4}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing && <Spinner />}
                                Sign in
                            </Button>
                        </div>

                        <p className="text-center text-sm text-[#A3A3A3]">
                            New here?{' '}
                            <TextLink
                                href={register()}
                                tabIndex={6}
                                className="font-bold tracking-wide text-[#F97316] underline-offset-4 hover:underline"
                            >
                                Create account
                            </TextLink>
                        </p>
                    </>
                )}
            </Form>
        </>
    );
}

Login.layout = {
    title: '',
    description: '',
};
