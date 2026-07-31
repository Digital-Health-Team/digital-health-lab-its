export type PublishKind = "project" | "publication";

export type PublishStatus = "pending" | "approved" | "rejected";

/** One card on /publish. Shape is built by PublishController::projectRow/publicationRow. */
export interface PublishItem {
    id: number;
    kind: PublishKind;
    /** Display string already — "Projects" | "Journals" | "Papers". */
    category: string;
    status: PublishStatus;
    title: string;
    coverUrl: string | null;
    date: string;
    /** Null until an admin approves it — there is no public page yet. */
    detailHref: string | null;
    editHref: string;
    deleteUrl: string;
    canEdit: boolean;
    /** Always true for the owner — on approved work the button files a removal request. */
    canDelete: boolean;
    withdrawalRequested: boolean;
}

/** Raw columns for the edit form — never the English overlay, or a save would clobber the original. */
export interface PublishFormItem {
    id: number;
    title: string;
    category: string;
    // Project fields
    listing_type?: string | null;
    caption?: string | null;
    highlights?: string[];
    includes?: string[];
    license?: string;
    version?: string | null;
    format?: string | null;
    // Publication fields
    author?: string;
    abstract?: string | null;
    keywords?: string[];
    doi?: string | null;
    journal?: string | null;
    pmid?: string | null;
    published_at?: string | null;
    // Both — feed the upload zone's preview on edit
    description?: string[];
    coverUrl?: string | null;
    existingFiles?: { name: string; size: string | null; url: string | null }[];
    pdfUrl?: string | null;
    pdfName?: string | null;
    pdfSize?: string | null;
}
