import { Form, Head } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

type Props = {
    passwordRules: string;
};

const fieldClassName =
    'h-11 rounded-none border-[#262626] bg-[#141414] text-[#F5F5F0] placeholder:text-[#737373] focus-visible:border-[#F97316] focus-visible:ring-[#F97316]/40';
const labelClassName =
    'text-xs font-semibold tracking-[0.15em] text-[#A3A3A3] uppercase';
const buttonClassName =
    'h-12 w-full rounded-none bg-[#F97316] font-bold tracking-widest text-[#0B0B0B] uppercase shadow-none hover:bg-orange-400 focus-visible:border-[#F97316] focus-visible:ring-[#F97316]';

export default function Register({ passwordRules }: Props) {
    return (
        <>
            <Head title="Join the gym" />

            <header className="mb-8 space-y-3">
                <p className="text-xs font-bold tracking-[0.2em] text-[#F97316] uppercase">
                    Join the gym
                </p>
                <h1 className="text-4xl leading-none font-black tracking-tight text-[#F5F5F0] uppercase">
                    Start training
                </h1>
                <p className="text-sm text-[#A3A3A3]">
                    Create your account and get moving.
                </p>
            </header>

            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor="name"
                                    className={labelClassName}
                                >
                                    Name
                                </Label>
                                <Input
                                    id="name"
                                    className={fieldClassName}
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="name"
                                    name="name"
                                    placeholder="Full name"
                                />
                                <InputError
                                    message={errors.name}
                                    className="text-[#EF4444]"
                                />
                            </div>

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
                                    required
                                    tabIndex={2}
                                    autoComplete="email"
                                    name="email"
                                    placeholder="name@example.com"
                                />
                                <InputError
                                    message={errors.email}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password"
                                    className={labelClassName}
                                >
                                    Password
                                </Label>
                                <PasswordInput
                                    id="password"
                                    required
                                    tabIndex={3}
                                    autoComplete="new-password"
                                    name="password"
                                    placeholder="Password"
                                    passwordrules={passwordRules}
                                    textToggle
                                    className={fieldClassName}
                                />
                                <InputError
                                    message={errors.password}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password_confirmation"
                                    className={labelClassName}
                                >
                                    Confirm password
                                </Label>
                                <PasswordInput
                                    id="password_confirmation"
                                    required
                                    tabIndex={4}
                                    autoComplete="new-password"
                                    name="password_confirmation"
                                    placeholder="Confirm password"
                                    passwordrules={passwordRules}
                                    textToggle
                                    className={fieldClassName}
                                />
                                <InputError
                                    message={errors.password_confirmation}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <Button
                                type="submit"
                                className={buttonClassName}
                                tabIndex={5}
                                disabled={processing}
                                data-test="register-user-button"
                            >
                                {processing && <Spinner />}
                                Create account
                            </Button>
                        </div>

                        <p className="text-center text-xs leading-5 text-[#A3A3A3]">
                            By signing up you agree to our Terms &amp; Privacy
                            Policy.
                        </p>
                        <p className="text-center text-sm text-[#A3A3A3]">
                            Already a member?{' '}
                            <TextLink
                                href={login()}
                                tabIndex={6}
                                className="font-bold tracking-wide text-[#F97316] underline-offset-4 hover:underline"
                            >
                                Sign in
                            </TextLink>
                        </p>
                    </>
                )}
            </Form>
        </>
    );
}

Register.layout = {
    title: '',
    description: '',
};
