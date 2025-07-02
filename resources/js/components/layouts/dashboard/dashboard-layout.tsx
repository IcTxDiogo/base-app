import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import { useSidebarPersistence } from '@/hooks/use-sidebar-persistence';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { AppSidebar } from './components/app-sidebar';
import { Breadcrumbs } from './components/breadcrumbs';

type DashboardLayoutProps = {
    breadcrumbs: BreadcrumbItem[];
    title?: string;
} & PropsWithChildren;

export default function DashboardLayout({ children, breadcrumbs, title }: DashboardLayoutProps) {
    const [open, setOpen] = useSidebarPersistence(true);
    return (
        <SidebarProvider open={open} onOpenChange={setOpen}>
            <Head title={title} />
            <AppSidebar />
            <SidebarInset>
                <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
                    <div className="flex items-center gap-2">
                        <SidebarTrigger className="-ml-1" />
                        <Breadcrumbs breadcrumbs={breadcrumbs} />
                    </div>
                </header>
                <div className="flex flex-1 flex-col gap-4 p-4 pt-0">
                    <div className="flex-1">{children}</div>
                </div>
            </SidebarInset>
        </SidebarProvider>
    );
}
