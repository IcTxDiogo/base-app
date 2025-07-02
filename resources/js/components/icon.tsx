import React from 'react';
import { IconComponent, iconMap, IconName } from './icon-map';

// As props que nosso componente aceita
interface IconProps extends React.SVGProps<SVGSVGElement> {
    name: IconName; // Usa o tipo que criamos para garantir que o nome é válido
    className?: string;
    size?: number;
}

export function Icon({ name, className, size, ...props }: IconProps) {
    // 1. Procura o componente de ícone no mapa usando o nome recebido
    const IconComponent: IconComponent | undefined = iconMap[name];

    // 2. Se o ícone não for encontrado, não renderiza nada (ou renderiza um ícone padrão)
    if (!IconComponent) {
        console.warn(`Ícone "${name}" não encontrado no icon-map.ts`);
        return null;
    }

    // 3. Renderiza o componente de ícone encontrado, passando as props adiante
    return <IconComponent className={className} size={size} {...props} />;
}
