import { Head, Link } from "@inertiajs/react";
import { ArrowRight, Printer, BookOpen, CheckCircle2, FlaskConical } from "lucide-react";

export default function Home() {
    return (
        <>
            <Head title="Medical Lab | 3D Printing & Publishing" />
            
            <div className="min-h-screen bg-teal-50 font-['Noto_Sans'] text-teal-900 flex flex-col selection:bg-cyan-600 selection:text-white">
                
                {/* Navigation / Header */}
                <header className="w-full max-w-7xl mx-auto px-6 py-8 flex justify-between items-center">
                    <div className="flex items-center gap-2">
                        <div className="w-10 h-10 bg-cyan-600 rounded-lg flex items-center justify-center text-white">
                            <FlaskConical size={24} />
                        </div>
                        <span className="font-['Figtree'] font-bold text-xl tracking-tight text-teal-900">
                            Bio<span className="text-cyan-600">Print</span>Lab
                        </span>
                    </div>
                    
                    <nav className="hidden md:flex gap-8 font-medium">
                        <a href="#services" className="text-teal-900/80 hover:text-cyan-600 transition-colors duration-200">Services</a>
                        <a href="#about" className="text-teal-900/80 hover:text-cyan-600 transition-colors duration-200">About</a>
                        <a href="#contact" className="text-teal-900/80 hover:text-cyan-600 transition-colors duration-200">Contact</a>
                    </nav>
                    
                    <div className="flex gap-4">
                        <Link 
                            href="/login" 
                            className="hidden md:flex items-center justify-center px-5 py-2.5 font-medium text-cyan-600 bg-white border border-cyan-100 rounded-lg shadow-sm hover:shadow hover:border-cyan-200 transition-all duration-200"
                        >
                            Log in
                        </Link>
                    </div>
                </header>

                {/* Main Content */}
                <main className="flex-1 flex flex-col justify-center w-full max-w-7xl mx-auto px-6 py-12 md:py-20 lg:py-28">
                    
                    {/* Hero Section */}
                    <div className="max-w-4xl mx-auto text-center space-y-8">
                        <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100/50 text-cyan-700 font-medium text-sm mb-4 border border-cyan-200/50">
                            <span className="relative flex h-2 w-2">
                              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-500 opacity-75"></span>
                              <span className="relative inline-flex rounded-full h-2 w-2 bg-cyan-600"></span>
                            </span>
                            Now accepting new research partnerships
                        </div>
                        
                        <h1 className="font-['Figtree'] text-5xl md:text-6xl lg:text-7xl font-bold leading-[1.1] tracking-tight">
                            Advancing Medical Research Through <span className="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-cyan-400">Precision Engineering</span>
                        </h1>
                        
                        <p className="text-xl md:text-2xl text-teal-900/70 max-w-2xl mx-auto leading-relaxed">
                            We bridge the gap between clinical theory and physical reality with ISO-certified 3D bio-printing and high-impact paper publishing services.
                        </p>
                        
                        {/* Benefits / Services Quick Look */}
                        <div className="flex flex-col sm:flex-row justify-center gap-6 pt-6 pb-10">
                            <div className="flex items-center justify-center gap-3 bg-white px-6 py-4 rounded-xl shadow-sm border border-teal-100">
                                <div className="text-cyan-600 bg-cyan-50 p-2 rounded-lg">
                                    <Printer size={24} />
                                </div>
                                <span className="font-medium text-lg">Medical 3D Printing</span>
                            </div>
                            
                            <div className="flex items-center justify-center gap-3 bg-white px-6 py-4 rounded-xl shadow-sm border border-teal-100">
                                <div className="text-cyan-600 bg-cyan-50 p-2 rounded-lg">
                                    <BookOpen size={24} />
                                </div>
                                <span className="font-medium text-lg">Academic Publishing</span>
                            </div>
                        </div>
                        
                        {/* CTA */}
                        <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <Link 
                                href="/register" 
                                className="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-green-500/20 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer focus:outline-none focus:ring-4 focus:ring-green-500/30"
                                aria-label="Start your project with us"
                            >
                                Start Your Project
                                <ArrowRight size={20} />
                            </Link>
                            
                            <a 
                                href="#learn-more" 
                                className="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl font-medium text-teal-900 hover:bg-teal-100/50 transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-teal-500/20"
                            >
                                Learn more
                            </a>
                        </div>
                        
                        {/* Trust Indicators */}
                        <div className="mt-16 pt-10 border-t border-teal-200/50">
                            <p className="text-sm font-semibold text-teal-900/50 uppercase tracking-wider mb-6">
                                Trusted by leading research institutions
                            </p>
                            <div className="flex flex-wrap justify-center gap-8 md:gap-16 opacity-60 grayscale">
                                {/* Placeholders for institution logos */}
                                <div className="font-['Figtree'] font-bold text-xl">Stanford Med</div>
                                <div className="font-['Figtree'] font-bold text-xl">Johns Hopkins</div>
                                <div className="font-['Figtree'] font-bold text-xl">Mayo Clinic</div>
                                <div className="font-['Figtree'] font-bold text-xl">Harvard Med</div>
                            </div>
                        </div>

                    </div>
                </main>

                {/* Footer */}
                <footer className="w-full border-t border-teal-200/50 py-8 text-center text-teal-900/60">
                    <p className="text-sm">&copy; {new Date().getFullYear()} BioPrintLab. All rights reserved.</p>
                </footer>
            </div>
        </>
    );
}
