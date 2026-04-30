export default function IdigLogo({ className = "" }: { className?: string }) {
    return (
        <div className={`flex items-center gap-2.5 ${className}`}>
            <div className="relative w-10 h-10 flex items-center justify-center">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-10 h-10">
                    <circle cx="20" cy="20" r="19" stroke="#22D3EE" strokeWidth="2" />
                    <circle cx="20" cy="20" r="13" fill="#00426D" />
                    <path d="M14 20 C14 16.686 16.686 14 20 14 C23.314 14 26 16.686 26 20 C26 23.314 23.314 26 20 26" stroke="#22D3EE" strokeWidth="2" strokeLinecap="round" />
                    <circle cx="20" cy="20" r="3" fill="#22D3EE" />
                </svg>
            </div>
            <div className="flex flex-col leading-tight">
                <span className="text-white font-display font-bold text-base tracking-tight">iDIG</span>
                <span className="text-secondary-400 font-body text-[10px] font-medium tracking-widest uppercase">Health Tech.</span>
            </div>
        </div>
    );
}
