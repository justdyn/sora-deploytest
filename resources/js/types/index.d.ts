import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export interface Pelanggan {
    id: number;
    name: string;
    email: string;
    no_telepon?: string;
    profile_photo?: string;
}

export interface Reservation {
    id: number;
    pelanggan: Pelanggan;
    reservation_date: string;
    status: 'pending' | 'confirmed' | 'rejected';
    staff_whatsapp?: string;
}

export interface PageProps<T = Record<string, unknown>> {
    auth: {
        user: User;
    };
    errors: Record<string, string>;
} & T;
