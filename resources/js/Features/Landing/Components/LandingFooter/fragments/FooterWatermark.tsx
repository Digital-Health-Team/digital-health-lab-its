export default function FooterWatermark() {
    return (
        <div className="relative z-0 mt-16 md:mt-24 flex justify-center items-center w-full select-none pointer-events-none">
            <span className="font-display font-black tracking-tighter text-[clamp(4rem,16vw,20rem)] leading-[0.8] text-slate-100 whitespace-nowrap">
                IDIG HTECH
            </span>
        </div>
    );
}
