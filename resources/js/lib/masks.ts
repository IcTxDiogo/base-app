import { z } from 'zod';

export interface MaskConfig {
    apply: (value: string) => string;
    unmask: (value: string) => string;
    schema: z.ZodString;
}

const masks = {
    hexColor: {
        apply: (value) => {
            const cleaned = value.replace(/[^0-9a-fA-F]/g, '');
            const limited = cleaned.slice(0, 6);
            return `#${limited}`;
        },
        unmask: (value) => {
            return value.replace('#', '');
        },
        schema: z
            .string({ required_error: 'O código da cor é obrigatório.' })
            .regex(/^#[0-9a-fA-F]{6}$/i, 'Formato de cor inválido. Use o formato #RRGGBB.'),
    },
} satisfies Record<string, MaskConfig>;

export type Mask = keyof typeof masks;

export function applyMask(value: string, mask: Mask): string {
    if (!mask) return value;
    return masks[mask].apply(value);
}

export function unmask(value: string, mask: Mask): string {
    if (!mask) return value;
    return masks[mask].unmask(value);
}

export function getMaskSchema(mask: Mask): z.ZodString {
    return masks[mask].schema;
}

export const schemas = {
    hexColor: getMaskSchema('hexColor'),
};
