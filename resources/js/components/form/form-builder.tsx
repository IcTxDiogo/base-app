import { Button } from '@/components/ui/button';
import {
    FormDescription,
    FormItem,
    FormLabel,
    FormMessage,
    Form as ShadcnForm,
    FormControl as ShadcnFormControl,
    FormField as ShadcnFormField,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { applyMask, type Mask } from '@/lib/masks';
import { cn } from '@/lib/utils';
import type { ComponentProps, ReactNode } from 'react';
import { useFormContext, type ControllerRenderProps, type FieldValues, type Path, type UseFormReturn } from 'react-hook-form';

type InputProps = Omit<ComponentProps<typeof Input>, 'name'>;

interface FormRootProps<T extends FieldValues> extends Omit<ComponentProps<'form'>, 'onSubmit'> {
    form: UseFormReturn<T>;
    onSubmit: (values: T) => void | Promise<void>;
}

function FormRoot<T extends FieldValues>({ form, onSubmit, children, className, ...props }: FormRootProps<T>) {
    return (
        <ShadcnForm {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className={cn('flex flex-col gap-4', className)} {...props}>
                {children}
            </form>
        </ShadcnForm>
    );
}

const FormContent = ({ className, ...props }: ComponentProps<'div'>) => <div className={cn('flex flex-col gap-y-6', className)} {...props} />;

interface FormFieldLayoutProps<T extends FieldValues> {
    name: Path<T>;
    label: string;
    description?: string;
    children: (field: ControllerRenderProps<T, Path<T>>) => ReactNode;
}

function FormFieldLayout<T extends FieldValues>({ name, label, description, children }: FormFieldLayoutProps<T>) {
    const { control } = useFormContext<T>();

    return (
        <ShadcnFormField
            control={control}
            name={name}
            render={({ field }) => (
                <FormItem>
                    <FormLabel>{label}</FormLabel>
                    {children(field)}
                    {description && <FormDescription>{description}</FormDescription>}
                    <FormMessage className="text-xs" />
                </FormItem>
            )}
        />
    );
}

type BaseFieldProps<T extends FieldValues> = {
    name: Path<T>;
    label: string;
    description?: string;
};

type FormInputProps<T extends FieldValues> = BaseFieldProps<T> & InputProps;

function FormInput<T extends FieldValues>({ name, label, description, ...props }: FormInputProps<T>) {
    return (
        <FormFieldLayout name={name} label={label} description={description}>
            {(field) => (
                <ShadcnFormControl>
                    <Input {...props} {...field} />
                </ShadcnFormControl>
            )}
        </FormFieldLayout>
    );
}

type FormMaskedInputProps<T extends FieldValues> = FormInputProps<T> & { mask: Mask };

function FormMaskedInput<T extends FieldValues>({ mask, name, label, description, ...props }: FormMaskedInputProps<T>) {
    return (
        <FormFieldLayout name={name} label={label} description={description}>
            {({ onChange, ...field }) => (
                <ShadcnFormControl>
                    <Input
                        {...props}
                        {...field}
                        onChange={(e) => {
                            const { value } = e.target;
                            e.target.value = applyMask(value, mask);
                            onChange(e);
                        }}
                    />
                </ShadcnFormControl>
            )}
        </FormFieldLayout>
    );
}

type FormFieldProps<T extends FieldValues> = BaseFieldProps<T> & {
    render: (field: ControllerRenderProps<T, Path<T>>) => ReactNode;
};

function FormField<T extends FieldValues>({ name, label, description, render }: FormFieldProps<T>) {
    return (
        <FormFieldLayout name={name} label={label} description={description}>
            {(field) => <ShadcnFormControl>{render(field)}</ShadcnFormControl>}
        </FormFieldLayout>
    );
}

type FormCustomProps<T extends FieldValues> = BaseFieldProps<T> & {
    render: (field: ControllerRenderProps<T, Path<T>>) => ReactNode;
};

function FormCustom<T extends FieldValues>({ name, label, description, render }: FormCustomProps<T>) {
    return (
        <FormFieldLayout name={name} label={label} description={description}>
            {(field) => render(field)}
        </FormFieldLayout>
    );
}

const FormFooter = ({ ...props }: ComponentProps<typeof Button>) => <Button type="submit" {...props} />;

export const Form = {
    Root: FormRoot,
    Content: FormContent,
    Input: FormInput,
    MaskedInput: FormMaskedInput,
    Field: FormField,
    Custom: FormCustom,
    Footer: FormFooter,
};
