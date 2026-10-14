import { Eye, EyeOff } from 'lucide-react';
import type { ComponentProps, Ref } from 'react';
import { useState } from 'react';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

export default function PasswordInput({
    className,
    ref,
    textToggle = false,
    ...props
}: Omit<ComponentProps<'input'>, 'type'> & {
    ref?: Ref<HTMLInputElement>;
    textToggle?: boolean;
}) {
    const [showPassword, setShowPassword] = useState(false);

    return (
        <div className="relative">
            <Input
                type={showPassword ? 'text' : 'password'}
                className={cn(textToggle ? 'pr-16' : 'pr-10', className)}
                ref={ref}
                {...props}
            />
            <button
                type="button"
                onClick={() => setShowPassword((prev) => !prev)}
                className={cn(
                    'absolute inset-y-0 right-0 flex items-center px-3 text-[#A3A3A3] hover:text-[#F5F5F0] focus-visible:ring-2 focus-visible:ring-[#F97316] focus-visible:outline-none focus-visible:ring-inset',
                    textToggle
                        ? 'text-xs font-bold tracking-widest uppercase'
                        : 'rounded-r-md',
                )}
                aria-label={showPassword ? 'Hide password' : 'Show password'}
                aria-pressed={showPassword}
            >
                {textToggle ? (
                    showPassword ? (
                        'HIDE'
                    ) : (
                        'SHOW'
                    )
                ) : showPassword ? (
                    <EyeOff className="size-4" />
                ) : (
                    <Eye className="size-4" />
                )}
            </button>
        </div>
    );
}
