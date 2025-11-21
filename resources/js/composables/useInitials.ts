export function getInitials(first_name?: string, last_name?: string): string {
    if (!first_name && !last_name) return '';
    if (!first_name) return last_name!.charAt(0).toUpperCase();
    if (!last_name) return first_name.charAt(0).toUpperCase();

    return `${first_name.charAt(0)}${last_name.charAt(0)}`.toUpperCase();
}

export function useInitials() {
    return { getInitials };
}
