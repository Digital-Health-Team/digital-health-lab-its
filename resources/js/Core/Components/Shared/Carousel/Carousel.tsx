import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import gsap from "gsap";
import { Box } from "@/Core/Components/Common/Box";
import { Image } from "@/Core/Components/Common/Image";
import { cn } from "@/Core/Utils/utils";

export interface CarouselItem {
    id: number | string;
    image: string;
    alt: string;
}

export interface CarouselProps {
    items: CarouselItem[];
    /** Total width (px) of the carousel box. Item width is this minus padding. */
    baseWidth?: number;
    autoplay?: boolean;
    autoplayDelay?: number;
    pauseOnHover?: boolean;
    loop?: boolean;
    /** 1:1 aspect ratio with a circular container and circular items. */
    round?: boolean;
    className?: string;
}

const GAP = 16;
const PADDING = 16;
const DURATION = 0.6;
const EASE = "power3.out";
const DRAG_BUFFER = 8; // px — below this a pointer-up reads as a click, not a swipe
const VELOCITY_THRESHOLD = 500; // px/s

/**
 * Coverflow carousel driven entirely by GSAP (the React Bits original uses
 * motion/react). One tween moves the track; every slide's rotateY is derived
 * from the track's live x, so drag and tween share the same paint path.
 */
export default function Carousel({
    items,
    baseWidth = 300,
    autoplay = false,
    autoplayDelay = 3000,
    pauseOnHover = false,
    loop = false,
    round = false,
    className,
}: CarouselProps) {
    const itemWidth = baseWidth - PADDING * 2;
    const offset = itemWidth + GAP;

    // Clone last/first around the real items so the wrap-around is seamless.
    const looping = loop && items.length > 1;
    const slides = useMemo(
        () => (looping ? [items[items.length - 1], ...items, items[0]] : items),
        [items, looping],
    );
    const startPos = looping ? 1 : 0;

    const trackRef = useRef<HTMLDivElement>(null);
    const slideRefs = useRef<(HTMLDivElement | null)[]>([]);
    const tweenRef = useRef<gsap.core.Tween | null>(null);
    const posRef = useRef(startPos);
    const xRef = useRef(-startPos * offset);
    const hoverRef = useRef(false);
    const dragRef = useRef<{
        startX: number;
        baseX: number;
        lastX: number;
        lastT: number;
        velocity: number;
    } | null>(null);

    const [active, setActive] = useState(0);

    const toActive = useCallback(
        (pos: number) => {
            if (items.length === 0) return 0;
            return looping
                ? (pos - 1 + items.length) % items.length
                : Math.min(pos, items.length - 1);
        },
        [items.length, looping],
    );

    /** Single source of truth for what the DOM shows at a given track x. */
    const paint = useCallback(
        (x: number) => {
            const track = trackRef.current;
            if (!track) return;
            gsap.set(track, {
                x,
                perspectiveOrigin: `${-x + itemWidth / 2}px 50%`,
            });
            slideRefs.current.forEach((el, i) => {
                if (el) gsap.set(el, { rotateY: (-90 * (x + i * offset)) / offset });
            });
        },
        [itemWidth, offset],
    );

    const jump = useCallback(
        (pos: number) => {
            tweenRef.current?.kill();
            posRef.current = pos;
            xRef.current = -pos * offset;
            paint(xRef.current);
            setActive(toActive(pos));
        },
        [offset, paint, toActive],
    );

    const go = useCallback(
        (next: number) => {
            const pos = gsap.utils.clamp(0, slides.length - 1, next);
            posRef.current = pos;
            setActive(toActive(pos));

            const target = -pos * offset;
            const proxy = { x: xRef.current };
            tweenRef.current?.kill();
            tweenRef.current = gsap.to(proxy, {
                x: target,
                duration: DURATION,
                ease: EASE,
                onUpdate: () => {
                    xRef.current = proxy.x;
                    paint(proxy.x);
                },
                onComplete: () => {
                    if (!looping) return;
                    // Landed on a clone — hop to its real twin without animating.
                    if (pos === slides.length - 1) jump(1);
                    else if (pos === 0) jump(items.length);
                },
            });
        },
        [items.length, jump, looping, offset, paint, slides.length, toActive],
    );

    // Initial paint, and re-anchor whenever the geometry or item count changes.
    useEffect(() => {
        jump(startPos);
    }, [jump, startPos]);

    useEffect(() => {
        const reduced =
            typeof window !== "undefined" &&
            window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        if (!autoplay || reduced || slides.length <= 1) return;

        const id = setInterval(() => {
            if (pauseOnHover && hoverRef.current) return;
            if (dragRef.current) return;
            go(posRef.current + 1);
        }, autoplayDelay);
        return () => clearInterval(id);
    }, [autoplay, autoplayDelay, go, pauseOnHover, slides.length]);

    useEffect(() => () => void tweenRef.current?.kill(), []);

    const onPointerDown = (e: React.PointerEvent<HTMLDivElement>) => {
        tweenRef.current?.kill();
        e.currentTarget.setPointerCapture(e.pointerId);
        dragRef.current = {
            startX: e.clientX,
            baseX: xRef.current,
            lastX: e.clientX,
            lastT: e.timeStamp,
            velocity: 0,
        };
    };

    const onPointerMove = (e: React.PointerEvent<HTMLDivElement>) => {
        const drag = dragRef.current;
        if (!drag) return;

        const dt = e.timeStamp - drag.lastT;
        if (dt > 0) drag.velocity = ((e.clientX - drag.lastX) / dt) * 1000;
        drag.lastX = e.clientX;
        drag.lastT = e.timeStamp;

        let x = drag.baseX + (e.clientX - drag.startX);
        if (!looping) x = gsap.utils.clamp(-(slides.length - 1) * offset, 0, x);
        xRef.current = x;
        paint(x);
    };

    const onPointerUp = (e: React.PointerEvent<HTMLDivElement>) => {
        const drag = dragRef.current;
        if (!drag) return;
        dragRef.current = null;

        const delta = e.clientX - drag.startX;
        const direction =
            delta < -DRAG_BUFFER || drag.velocity < -VELOCITY_THRESHOLD
                ? 1
                : delta > DRAG_BUFFER || drag.velocity > VELOCITY_THRESHOLD
                  ? -1
                  : 0;
        go(posRef.current + direction);
    };

    if (items.length === 0) return null;

    return (
        <Box
            role="group"
            aria-roledescription="carousel"
            className={cn(
                "relative select-none overflow-hidden",
                round ? "rounded-full" : "rounded-[24px]",
                className,
            )}
            style={{
                width: baseWidth,
                height: round ? baseWidth : undefined,
                padding: PADDING,
            }}
            onPointerEnter={() => {
                hoverRef.current = true;
            }}
            onPointerLeave={() => {
                hoverRef.current = false;
            }}
        >
            <Box
                ref={trackRef}
                className="flex cursor-grab touch-pan-y active:cursor-grabbing"
                style={{ width: itemWidth, gap: GAP, perspective: 1000 }}
                onPointerDown={onPointerDown}
                onPointerMove={onPointerMove}
                onPointerUp={onPointerUp}
                onPointerCancel={onPointerUp}
            >
                {slides.map((item, i) => (
                    <Box
                        key={`${item.id}-${i}`}
                        ref={(el: HTMLDivElement | null) => {
                            slideRefs.current[i] = el;
                        }}
                        className={cn(
                            "relative shrink-0 overflow-hidden bg-white/5",
                            round ? "rounded-full" : "rounded-[12px]",
                        )}
                        style={{
                            width: itemWidth,
                            height: round ? itemWidth : "100%",
                        }}
                    >
                        <Image
                            src={item.image}
                            alt={item.alt}
                            objectFit="contain"
                            draggable={false}
                            className="pointer-events-none h-full w-full"
                        />
                    </Box>
                ))}
            </Box>

            <Box
                className={cn(
                    // Dots sit over photography, so they carry their own scrim.
                    "absolute left-1/2 z-20 flex -translate-x-1/2 items-center gap-2 rounded-full bg-black/35 px-3 py-2 backdrop-blur-sm",
                    round ? "bottom-[10%]" : "bottom-4",
                )}
            >
                {items.map((item, i) => (
                    <Box
                        as="button"
                        key={item.id}
                        type="button"
                        aria-label={`Go to slide ${i + 1}`}
                        aria-current={active === i}
                        onClick={() => go(looping ? i + 1 : i)}
                        className={cn(
                            "h-2 w-2 cursor-pointer rounded-full transition-all duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white",
                            active === i
                                ? "scale-125 bg-white"
                                : "bg-white/40 hover:bg-white/70",
                        )}
                    />
                ))}
            </Box>
        </Box>
    );
}
