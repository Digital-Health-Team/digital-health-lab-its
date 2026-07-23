import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { prefersReducedMotion } from "@/Features/Landing/Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

/**
 * Profile reveal — masthead settles in as the avatar scales into its teal
 * ring and the role hairline draws, then bio/expertise and the teammates
 * grid fade up as they enter view.
 */
export function useTeamMemberShowAnimation(containerRef: RefObject<HTMLElement | null>) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;

            if (prefersReducedMotion()) {
                gsap.from([".team-show-masthead", ".team-show-body", ".team-show-related"], {
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    scrollTrigger: { trigger: section, start: "top 80%" },
                });
                return;
            }

            const tl = gsap.timeline();

            tl.from(".team-show-masthead > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.12,
                ease: "power3.out",
            });

            tl.from(
                ".team-show-avatar",
                { scale: 0.85, autoAlpha: 0, duration: 0.7, ease: "power3.out" },
                "-=0.5",
            );

            tl.from(
                ".team-show-hairline",
                { scaleX: 0, duration: 0.5, ease: "power2.out", transformOrigin: "left center" },
                "-=0.2",
            );

            tl.from(
                ".team-show-contact > *",
                { opacity: 0, y: 10, duration: 0.4, stagger: 0.06, ease: "power3.out" },
                "-=0.15",
            );

            gsap.from(".team-show-body > *", {
                opacity: 0,
                y: 16,
                duration: 0.6,
                stagger: 0.1,
                ease: "power3.out",
                scrollTrigger: { trigger: ".team-show-body", start: "top 85%" },
            });

            const expertisePills = section.querySelectorAll(".team-show-expertise-pill");
            if (expertisePills.length) {
                gsap.from(expertisePills, {
                    opacity: 0,
                    y: 8,
                    duration: 0.4,
                    stagger: 0.04,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".team-show-expertise", start: "top 88%" },
                });
            }

            const educationItems = section.querySelectorAll(".team-show-education-item");
            if (educationItems.length) {
                gsap.from(educationItems, {
                    opacity: 0,
                    x: -8,
                    duration: 0.4,
                    stagger: 0.05,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".team-show-education", start: "top 88%" },
                });
            }

            const projectItems = section.querySelectorAll(".team-show-project-item");
            if (projectItems.length) {
                gsap.from(projectItems, {
                    opacity: 0,
                    y: 12,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".team-show-projects", start: "top 85%" },
                });
            }

            const teammateCards = section.querySelectorAll(".team-show-teammate-card");
            if (teammateCards.length) {
                gsap.from(teammateCards, {
                    y: 24,
                    autoAlpha: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".team-show-related", start: "top 85%" },
                });
            }
        },
        { scope: containerRef },
    );
}
