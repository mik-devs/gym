import { Form, Head } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';

type Props = {
    token: string;
    email: string;
    passwordRules: string;
};

const fieldClassName =
    'h-11 rounded-none border-[#262626] bg-[#0B0B0B] text-[#F5F5F0] placeholder:text-[#737373] focus-visible:border-[#F97316] focus-visible:ring-[#F97316]/40';
const labelClassName =
    'text-xs font-semibold tracking-[0.15em] text-[#A3A3A3] uppercase';

export default function ResetPassword({ token, email, passwordRules }: Props) {
    return (
        <>
            <Head title="Reset password" />

            <header className="mb-8 space-y-3">
                <p className="text-xs font-bold tracking-[0.2em] text-[#F97316] uppercase">
                    Member support
                </p>
                <h1 className="text-3xl font-black tracking-tight text-[#F5F5F0] uppercase">
                    Set new password
                </h1>
                <p className="text-sm leading-6 text-[#A3A3A3]">
                    Choose a new password to secure your account.
                </p>
            </header>

            <Form
                {...update.form()}
                transform={(data) => ({ ...data, token, email })}
                resetOnSuccess={['password', 'password_confirmation']}
            >
                {({ processing, errors }) => (
                    <div className="grid gap-5">
                        <div className="grid gap-2">
                            <Label htmlFor="email" className={labelClassName}>
                                Email
                            </Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                autoComplete="email"
                                value={email}
                                className={fieldClassName}
                                readOnly
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
                                New password
                            </Label>
                            <PasswordInput
                                id="password"
                                name="password"
                                autoComplete="new-password"
                                className={fieldClassName}
                                autoFocus
                                placeholder="New password"
                                passwordrules={passwordRules}
                                textToggle
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
                                name="password_confirmation"
                                autoComplete="new-password"
                                className={fieldClassName}
                                placeholder="Confirm password"
                                passwordrules={passwordRules}
                                textToggle
                            />
                            <InputError
                                message={errors.password_confirmation}
                                className="text-[#EF4444]"
                            />
                        </div>

                        <Button
                            type="submit"
                            className="mt-2 h-12 w-full rounded-none bg-[#F97316] font-bold tracking-widest text-[#0B0B0B] uppercase shadow-none hover:bg-orange-400 focus-visible:border-[#F97316] focus-visible:ring-[#F97316]"
                            disabled={processing}
                            data-test="reset-password-button"
                        >
                            {processing && <Spinner />}
                            Reset password
                        </Button>
                    </div>
                )}
            </Form>
        </>
    );
}

ResetPassword.layout = {
    title: '',
    description: '',
};
