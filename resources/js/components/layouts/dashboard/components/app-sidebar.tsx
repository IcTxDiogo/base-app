import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader } from '@/components/ui/sidebar';
import DashboardLogo from './dashboard-logo';
import NavMain from './nav-main';
import { NavUser } from './nav-user';

export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
    return (
        <Sidebar collapsible="icon" {...props}>
            <SidebarHeader>
                <DashboardLogo />
            </SidebarHeader>
            <SidebarContent>
                <NavMain />
            </SidebarContent>
            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
