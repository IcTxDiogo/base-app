import { Icon } from '@/components/icon';
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export default function NavMain() {
    const { navigation } = usePage<SharedData>().props;

    return (
        <SidebarGroup>
            <SidebarGroupContent className="flex flex-col gap-2">
                <SidebarMenu>
                    {navigation.map((item) =>
                        'items' in item ? (
                            <SidebarGroup key={item.title} title={item.title}>
                                <SidebarGroupContent>
                                    {item.items.map((subItem) => (
                                        <SidebarMenuItem key={subItem.title}>
                                            <SidebarMenuButton tooltip={subItem.title} isActive={subItem.isActive}>
                                                <Icon name={subItem.icon} className="size-5" />
                                                <span>{subItem.title}</span>
                                            </SidebarMenuButton>
                                        </SidebarMenuItem>
                                    ))}
                                </SidebarGroupContent>
                            </SidebarGroup>
                        ) : (
                            <SidebarMenuItem key={item.title}>
                                <SidebarMenuButton tooltip={item.title} isActive={item.isActive}>
                                    <Icon name={item.icon} className="size-5" />
                                    <span>{item.title}</span>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        ),
                    )}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
