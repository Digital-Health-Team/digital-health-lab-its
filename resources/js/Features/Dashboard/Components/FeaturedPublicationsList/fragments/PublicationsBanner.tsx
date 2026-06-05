export default function PublicationsBanner() {
    return (
        <div
            className="relative overflow-hidden rounded-2xl h-36 mb-6 flex items-center justify-center"
            style={{
                background: "linear-gradient(135deg, #062E5C 0%, #00A8B5 60%, #22D3EE 100%)",
            }}
            aria-hidden="true"
        >
            {/* Books / document shapes */}
            <svg viewBox="0 0 400 144" fill="none" xmlns="http://www.w3.org/2000/svg" className="absolute inset-0 w-full h-full opacity-20">
                <rect x="20" y="30" width="40" height="84" rx="4" fill="white" />
                <rect x="26" y="36" width="28" height="4" rx="2" fill="#062E5C" />
                <rect x="26" y="44" width="20" height="3" rx="1.5" fill="#062E5C" />
                <rect x="70" y="20" width="36" height="90" rx="4" fill="white" opacity="0.8" />
                <rect x="76" y="28" width="24" height="4" rx="2" fill="#062E5C" />
                <rect x="340" y="25" width="40" height="84" rx="4" fill="white" />
                <rect x="314" y="40" width="38" height="74" rx="4" fill="white" opacity="0.7" />
                {/* Centre icon */}
                <circle cx="200" cy="72" r="36" fill="white" fillOpacity="0.15" />
                <path d="M188 72h24M200 60v24" stroke="white" strokeWidth="2.5" strokeLinecap="round"/>
            </svg>
            <p className="relative z-10 font-display text-xl font-bold text-white text-center drop-shadow-lg">
                Featured Publications
            </p>
        </div>
    );
}
