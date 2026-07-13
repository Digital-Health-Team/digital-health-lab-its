import { CollageItem } from "./organizationSection.type";

export interface CollaborationPartner {
    name: string;
    /** Two-line name-mark: [extrabold line, light-italic line] */
    nameLines: [string, string];
    type: string;
    period: string;
    description: string;
    align: "left" | "right";
    prints: CollageItem[];
}
