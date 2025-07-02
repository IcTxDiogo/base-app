import { useCallback, useEffect, useState } from 'react';

const SIDEBAR_LOCALSTORAGE_KEY = 'sidebar_collapsed';

export function useSidebarPersistence(defaultOpen = true) {
    const [open, setOpen] = useState<boolean>(() => {
        if (typeof window !== 'undefined') {
            const stored = localStorage.getItem(SIDEBAR_LOCALSTORAGE_KEY);
            if (stored !== null) return stored === 'true';
        }
        return defaultOpen;
    });

    useEffect(() => {
        localStorage.setItem(SIDEBAR_LOCALSTORAGE_KEY, String(open));
    }, [open]);

    const handleSetOpen = useCallback(
        (value: boolean | ((value: boolean) => boolean)) => {
            setOpen(typeof value === 'function' ? value(open) : value);
        },
        [open],
    );

    return [open, handleSetOpen] as const;
}
