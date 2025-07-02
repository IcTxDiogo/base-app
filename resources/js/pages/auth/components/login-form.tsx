import { zodResolver } from '@hookform/resolvers/zod';
import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

import { Form } from '@/components/form/form-builder';
import { Checkbox } from '@/components/ui/checkbox';
import { useExtendForm } from '@/hooks/use-extend-form';

const LoginSchema = z.object({
    email: z.string().email({ message: 'Por favor, insira um email válido.' }),
    password: z.string().min(1, { message: 'A senha é obrigatória.' }),
    remember: z.boolean(),
});

type LoginSchemaType = z.infer<typeof LoginSchema>;

export function LoginForm() {
    const form = useForm<LoginSchemaType>({
        resolver: zodResolver(LoginSchema),
        defaultValues: {
            email: '',
            password: '',
            remember: false,
        },
    });

    const { send, processing } = useExtendForm({
        form: form,
        method: 'post',
        url: route('login'),
    });

    useEffect(() => {
        return () => {
            form.resetField('password');
        };
    }, [form]);

    function onSubmit() {
        send({
            onError: () => {
                form.resetField('password');
            },
        });
    }

    return (
        <div className="w-full max-w-md">
            <Form.Root form={form} onSubmit={onSubmit}>
                <Form.Content>
                    <Form.Input name="email" label="Email" type="email" placeholder="seu@email.com" autoFocus autoComplete="username" />

                    <Form.Input name="password" label="Senha" type="password" autoComplete="current-password" />

                    <Form.Field
                        name="remember"
                        label=""
                        render={(field) => (
                            <div className="flex items-center space-x-2">
                                <Checkbox
                                    id="remember"
                                    checked={field.value}
                                    onCheckedChange={field.onChange}
                                    onBlur={field.onBlur}
                                    ref={field.ref}
                                />
                                <label htmlFor="remember" className="text-sm leading-none font-medium">
                                    Lembrar de mim
                                </label>
                            </div>
                        )}
                    />
                </Form.Content>

                <div className="mt-6 flex items-center justify-between">
                    <Form.Footer disabled={processing} className="ml-auto">
                        {processing ? 'Entrando...' : 'Entrar'}
                    </Form.Footer>
                </div>
            </Form.Root>
        </div>
    );
}
