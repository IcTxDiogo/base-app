import DashboardLayout from '@/components/layouts/dashboard/dashboard-layout';

const breadcrumbs = [{ title: 'Dashboard', href: route('dashboard.index') }];

export default function index() {
    return (
        <DashboardLayout breadcrumbs={breadcrumbs} title="Dashboard">
            <div className="flex h-screen flex-col items-center justify-center">
                <h1 className="mb-4 text-4xl font-bold">Dashboard</h1>
                <p className="text-lg">Welcome to your dashboard!</p>
            </div>
        </DashboardLayout>
    );
}
