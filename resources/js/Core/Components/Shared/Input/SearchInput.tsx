import { Search, X } from "lucide-react";
import { type InputHTMLAttributes } from "react";
import { Input } from "./Input";

interface SearchInputProps extends Omit<InputHTMLAttributes<HTMLInputElement>, "type"> {
    onClear?: () => void;
}

export function SearchInput({ value, onClear, ...props }: SearchInputProps) {
    return (
        <Input
            type="search"
            value={value}
            leftIcon={<Search className="h-4 w-4" />}
            rightIcon={
                value && onClear ? (
                    <button
                        type="button"
                        onClick={onClear}
                        className="flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                        aria-label="Clear search"
                    >
                        <X className="h-4 w-4" />
                    </button>
                ) : null
            }
            {...props}
        />
    );
}
