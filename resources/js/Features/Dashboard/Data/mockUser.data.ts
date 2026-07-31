import { type MockUser } from "../Types/user.type";

export const mockUser: MockUser = {
    name: "Bertha Amelia",
    email: "bertha@its.ac.id",
    avatarUrl: "https://picsum.photos/seed/bertha-avatar/64/64",
    /** English source string, and the t() key the topbar resolves it through. */
    role: "Student",
    unreadNotifications: 3,
};
