import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';
import Module from 'module';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}
export interface ModuleObjective  {
    number: string;
    objective: string;
}
export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}
export interface Course {
    id: number;
    title: string;
    prefix: string;
    number: string;
    objectives: [{
        number: string;
        text: string;
    }];
    created_at: string;
    updated_at: string;
    modules: Module[];
}

export interface Course_Assessment{
    assesment_type: string;
    aligned_module_objectives: string[];
    title: string;
  
}


export type BreadcrumbItemType = BreadcrumbItem;
