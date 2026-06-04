import { ChevronDown, User, ShoppingBag, Settings2, LogOut, Bookmark } from "lucide-react";
import { router } from "@inertiajs/react";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from "@/Core/Components/Shared";
import Avatar from "@/Core/Components/Shared/Avatar/Avatar";
import { mockUser } from "@/Features/Dashboard/Data/mockUser.data";

const menuItems = [
    { label: "My Profile", href: "/profile", icon: <User className="h-4 w-4" /> },
    { label: "My Orders", href: "/orders", icon: <ShoppingBag className="h-4 w-4" /> },
    { label: "My Uploads", href: "/uploads", icon: <Bookmark className="h-4 w-4" /> },
    { label: "Settings", href: "/settings", icon: <Settings2 className="h-4 w-4" /> },
];

export default function TopbarUserMenu() {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger className="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-slate-100 transition-colors duration-150">
                <Avatar
                    src={mockUser.avatarUrl}
                    name={mockUser.name}
                    size="sm"
                    statusDot
                />
                <span className="hidden sm:block text-sm font-medium text-slate-700 whitespace-nowrap">
                    {mockUser.name.split(" ")[0]}
                </span>
                <ChevronDown className="h-3.5 w-3.5 text-slate-400" />
            </DropdownMenuTrigger>

            <DropdownMenuContent width="w-56">
                <div className="px-3.5 py-3 border-b border-slate-100">
                    <p className="text-sm font-semibold text-slate-800">{mockUser.name}</p>
                    <p className="text-xs text-slate-500 mt-0.5">{mockUser.email}</p>
                </div>

                {menuItems.map((item) => (
                    <DropdownMenuItem
                        key={item.href}
                        icon={item.icon}
                        onClick={() => router.visit(item.href)}
                    >
                        {item.label}
                    </DropdownMenuItem>
                ))}

                <DropdownMenuSeparator />

                <DropdownMenuItem
                    destructive
                    icon={<LogOut className="h-4 w-4" />}
                    onClick={() => router.post("/logout")}
                >
                    Sign out
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
