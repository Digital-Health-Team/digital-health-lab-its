import { Head } from '@inertiajs/react';

export default function Home() {
    return (
        <>
            <Head title="Home" />
            <div className="flex min-h-screen items-center justify-center bg-slate-950">
                <div className="text-center">
                    <h1 className="text-4xl font-bold text-white">
                        Gretiva
                    </h1>
                    <p className="mt-2 text-slate-400">
                        React 19 + Inertia v3 + Wayfinder — Stack Online ✅
                    </p>
                </div>
            </div>
        </>
    );
}
