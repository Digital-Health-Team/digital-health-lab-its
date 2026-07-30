import { type Course } from "@/Features/Training/Types/course.type";

export type EventStatus = "upcoming" | "ongoing" | "past";

/** The taxonomy the /events tabs filter by — mirrors Event::CATEGORIES. */
export type EventCategory = "Exhibition" | "Seminar" | "Workshop";

/**
 * One row of the merged catalogue. Events and trainings stay separate models, so
 * `kind` picks the card; `group` is the shared taxonomy both are filtered by.
 * Note a training's own `category` is its topic tag, not its `group`.
 */
export type CatalogueItem =
    | ({ kind: "event"; group: EventCategory | null } & EventSummary)
    | ({
          kind: "training";
          group: "Workshop";
          status: EventStatus;
          startsAt?: string | null;
      } & Course);

/** Shape rendered by EventCard — matches EventController::toCardShape(). */
export interface EventSummary {
    id: number;
    slug: string;
    href: string;
    name: string;
    year: number;
    themeTitle: string;
    thumbnailUrl?: string | null;
    startsAt?: string | null;
    endsAt?: string | null;
    location?: string | null;
    category?: string | null;
    status: EventStatus;
    teamsCount: number;
}

export interface EventSpotlight extends EventSummary {
    subtitle?: string | null;
}

export interface EventTeamMember {
    id: number;
    name: string;
    roleInTeam: string;
}

export interface EventTeamProject {
    id: number;
    title: string;
    category?: string | null;
}

export interface EventTeam {
    id: number;
    name: string;
    courseName: string;
    members: EventTeamMember[];
    projects: EventTeamProject[];
}

export interface EventDetail extends EventSpotlight {
    description?: string | null;
    registrationUrl?: string | null;
    teams: EventTeam[];
}

export interface EventsHeroData {
    title: string;
    subtitle: string;
    ctaLabel: string;
    ctaHref: string;
    backgroundUrl: string;
}
