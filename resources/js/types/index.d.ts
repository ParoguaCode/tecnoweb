import type { PageProps } from '@inertiajs/core';
import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

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
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: {
        user: User;
        permisos: string[];
    }
    ziggy: Config & { location: string };
    flash: {
        success: string | null;
        error: string | null;
        // warning: string | null;
        // info: string | null;
        // message: string | null;
    };
}

export interface Paginacion<T> {
    current_page:   number;
    data:           T[];
    first_page_url: string;
    from:           number;
    last_page:      number;
    last_page_url:  string;
    links:          Link[];
    next_page_url:  string;
    path:           string;
    per_page:       number;
    prev_page_url:  null;
    to:             number;
    total:          number;
}

export interface Marca {
    id: number;
    nombre: string;
    created_at: string;
    updated_at: string;
}

export interface Rol {
    id: number;
    nombre: string;
    descripcion?: string;
    created_at: string;
    updated_at: string;
}

export interface Usuario {
    id: number;
    name: string;
    email: string;
    telefono?: number;
    direccion?: string;
    // tipo: string;
    rol_id: number;
    rol?: Rol;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
