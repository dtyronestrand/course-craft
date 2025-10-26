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
export interface ModuleObjective {
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
    pivot?: {
        role: string;
    };
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}
export interface Course {
    id: number;
    title: string;
    prefix: string;
    number: string;
    users: User[];
    objectives: [
        {
            number: string;
            objective: string;
        },
    ];
    created_at: string;
    updated_at: string;
    modules: Module[];
}

export interface Course_Assessment {
    assesment_type: string;
    title: string;
    aligned_module_objectives: string[];
}

export interface AssignmentSettings {
    submission_type: string[];
    point_value: number;
}
export interface DiscussionSettings {
    graded: boolean;
    point_value?: number;
}
export interface Itemable {
    title: string;
    content?: string;
    instructions?: string;
    criteria?: string;
    purpose?: string;
    prompt?: string;
    settings?: {
        submission_type?: string[];
        point_value?: number;
        graded?: boolean;
        time_limit?: number;
        attempts?: number;
    }
    questions?: {
        stem: string;
        type: string;
        options?: string[];
        correct_answer?: string | boolean;
    }[];
}

export interface ModuleItem {
    id: number;
    order_index: number;
    course_module_id: number;
    itemable_type: string;
    itemable_id: number;
    itemable: Itemable;
}


export interface CourseModule {
    id: number;
    order_index: number;
    number: number;
    title: string;
    module_objectives: ModuleObjective[];
    course_objectives: {
        number: string;
        objective: string;
    }[];
    assessments: Course_Assessment[];
    instructions: string[];
    materials: string[];
    needs: string[];
    items: ModuleItem[];
    overview: {
        content: string;
    }
}
export type BreadcrumbItemType = BreadcrumbItem;
