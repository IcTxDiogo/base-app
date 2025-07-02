import { LucideProps } from 'lucide-react';
import { type IconType } from 'react-icons';
import { MdOutlineSpaceDashboard } from 'react-icons/md';

export type IconComponent = React.FC<LucideProps> | IconType;

export const iconMap: Record<string, IconComponent> = {
    Dashboard: MdOutlineSpaceDashboard,
};

export type IconName = keyof typeof iconMap;
