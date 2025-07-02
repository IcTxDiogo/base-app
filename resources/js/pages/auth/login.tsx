import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { LoginForm } from './components/login-form';

export default function Login() {
    return (
        <div className="flex min-h-screen items-center justify-center bg-background p-4 text-foreground">
            <Head title="Login" />
            <Button type="button" asChild size={'icon'} variant={'outline'}>
                <Link href={route('home')} className="absolute top-4 left-4 text-sm text-muted-foreground hover:underline">
                    <ArrowLeft />
                </Link>
            </Button>
            <Card className="w-full max-w-md">
                <CardHeader>
                    <CardTitle>Acesse sua conta</CardTitle>
                </CardHeader>
                <CardContent>
                    <LoginForm />
                </CardContent>
            </Card>
        </div>
    );
}
