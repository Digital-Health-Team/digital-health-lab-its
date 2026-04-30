import { Button } from "@heroui/react";
import { ArrowRight } from "lucide-react";

export default function InnovationCTA() {
    return (
        <div className="relative -my-8 z-20 flex justify-center px-6">
            <Button
                size="lg"
                className="px-10 py-4 rounded-xl bg-secondary-500 hover:bg-secondary-400 border border-secondary-300/60 text-white font-display font-semibold shadow-lg shadow-secondary-500/30 inline-flex items-center gap-3 transition-all duration-300 hover:-translate-y-0.5 h-auto text-base"
            >
                Explore Our Innovations
                <ArrowRight size={18} />
            </Button>
        </div>
    );
}
