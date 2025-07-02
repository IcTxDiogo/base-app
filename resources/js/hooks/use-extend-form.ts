import type { VisitOptions } from '@inertiajs/core';
import { router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { type FieldValues, type Path, type UseFormReturn } from 'react-hook-form';

type ExtendFormProps<TFormValues extends FieldValues> = {
    form: UseFormReturn<TFormValues>;
    method: 'post' | 'put' | 'patch' | 'delete';
    url: string;
};

type ExtendFormReturn = {
    processing: boolean;
    send: (options?: Partial<VisitOptions>) => void;
};

export function useExtendForm<TFormValues extends FieldValues>({ form, method, url }: ExtendFormProps<TFormValues>): ExtendFormReturn {
    const [processing, setProcessing] = useState(false);
    const { errors: inertiaErrors } = usePage().props;

    useEffect(() => {
        const hasInertiaErrors = Object.keys(inertiaErrors).length > 0;
        if (hasInertiaErrors) {
            for (const key in inertiaErrors) {
                form.setError(key as Path<TFormValues>, {
                    type: 'server',
                    message: inertiaErrors[key],
                });
            }
        }
    }, [inertiaErrors, form]);

    useEffect(() => {
        const unsubscribeStart = router.on('start', () => setProcessing(true));
        const unsubscribeFinish = router.on('finish', () => setProcessing(false));

        return () => {
            unsubscribeStart();
            unsubscribeFinish();
        };
    }, []);

    function send(options?: Partial<VisitOptions>) {
        form.handleSubmit((validatedData) => {
            router[method](url, validatedData, options);
        })();
    }

    return { processing, send };
}
