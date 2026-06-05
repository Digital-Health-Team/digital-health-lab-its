interface CollageItem {
    left: string;
    top: string;
    width: string;
    aspectRatio: string;
    rot: number;
    shadow: string;
    z: number;
    image: string;
    center?: boolean;
}

interface PhotoCollageProps {
    items: CollageItem[];
    /** CSS class for the center item, e.g. "act-2-collage-center" */
    centerClass: string;
    /** CSS class for non-center items, e.g. "act-2-collage-item" */
    itemClass: string;
}

/**
 * Polaroid-style photo collage grid for OrganizationSection Acts 2 & 3.
 * Uses act-specific class names so GSAP timelines can target them independently.
 */
export default function PhotoCollage({ items, centerClass, itemClass }: PhotoCollageProps) {
    return (
        <>
            {items.map((item, i) => (
                <div
                    key={i}
                    className={`absolute group ${item.center ? centerClass : itemClass}`}
                    style={{
                        left: item.left,
                        top: item.top,
                        width: item.width,
                        aspectRatio: item.aspectRatio,
                        transform: `rotate(${item.rot}deg)`,
                        padding: "clamp(4px, 0.5vw, 8px)",
                        background: "white",
                        boxShadow: item.shadow,
                        zIndex: item.z,
                    }}
                >
                    <div className="relative w-full h-full overflow-hidden">
                        <img
                            src={item.image}
                            alt=""
                            className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-105"
                        />
                    </div>
                </div>
            ))}
        </>
    );
}
