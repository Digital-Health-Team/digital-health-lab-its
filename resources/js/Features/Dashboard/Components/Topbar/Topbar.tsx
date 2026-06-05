import TopbarMobileToggle from "./fragments/TopbarMobileToggle";
import TopbarSocialCluster from "./fragments/TopbarSocialCluster";
import TopbarSearch from "./fragments/TopbarSearch";
import TopbarLanguageSwitcher from "./fragments/TopbarLanguageSwitcher";
import TopbarNotifications from "./fragments/TopbarNotifications";
import TopbarUserMenu from "./fragments/TopbarUserMenu";

export default function Topbar() {
    return (
        <header className="sticky top-0 z-30 h-18 flex items-center gap-3 px-6 bg-white/95 backdrop-blur-md border-b border-slate-200">
            {/* Left: mobile burger + social links */}
            <div className="flex items-center gap-3 shrink-0">
                <TopbarMobileToggle />
                <TopbarSocialCluster />
                <div className="hidden sm:block h-6 w-px bg-slate-200" aria-hidden="true" />
            </div>

            {/* Center: search */}
            <TopbarSearch />

            {/* Right: language + notifications + user */}
            <div className="flex items-center gap-1 shrink-0">
                <TopbarLanguageSwitcher />
                <TopbarNotifications />
                <div className="h-6 w-px bg-slate-200 mx-1" aria-hidden="true" />
                <TopbarUserMenu />
            </div>
        </header>
    );
}
