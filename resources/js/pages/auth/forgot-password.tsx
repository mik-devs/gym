import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { email } from '@/routes/password';

const fieldClassName =
    'h-11 rounded-none border-[#262626] bg-[#0B0B0B] text-[#F5F5F0] placeholder:text-[#737373] focus-visible:border-[#F97316] focus-visible:ring-[#F97316]/40';

export default function ForgotPassword({ status }: { status?: string }) {
    return (
        <>
            <Head title="Account recovery" />

            <header className="mb-8 space-y-3">
                <p className="text-xs font-bold tracking-[0.2em] text-[#F97316] uppercase">
                    Member support
                </p>
                <h1 className="text-3xl font-black tracking-tight text-[#F5F5F0] uppercase">
                    Recover access
                </h1>
                <p className="text-sm leading-6 text-[#A3A3A3]">
                    Enter your account email and we’ll send you a password reset
                    link.
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

            <div className="space-y-7">
                <Form {...email.form()}>
                    {({ processing, errors }) => (
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor="email"
                                    className="text-xs font-semibold tracking-[0.15em] text-[#A3A3A3] uppercase"
                                >
                                    Email
                                </Label>
                                <Input
                                    id="email"
                                    className={fieldClassName}
                                    type="email"
                                    name="email"
                                    autoComplete="email"
                                    autoFocus
                                    placeholder="name@example.com"
                                />
                                <InputError
                                    message={errors.email}
                                    className="text-[#EF4444]"
                                />
                            </div>

                            <Button
                                className="h-12 w-full rounded-none bg-[#F97316] font-bold tracking-widest text-[#0B0B0B] uppercase shadow-none hover:bg-orange-400 focus-visible:border-[#F97316] focus-visible:ring-[#F97316]"
                                disabled={processing}
                                data-test="email-password-reset-link-button"
                            >
                                {processing && (
                                    <LoaderCircle className="size-4 animate-spin" />
                                )}
                                Send reset link
                            </Button>
                        </div>
                    )}
                </Form>

                <p className="text-center text-sm text-[#A3A3A3]">
                    Remembered your password?{' '}
                    <TextLink
                        href={login()}
                        className="font-bold text-[#F97316] underline-offset-4 hover:underline"
                    >
                        Sign in
                    </TextLink>
                </p>
            </div>
        </>
    );
}

ForgotPassword.layout = {
    title: '',
    description: '',
};
