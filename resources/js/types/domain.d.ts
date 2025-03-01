export interface Domain {
    id: number;
    name: string;
    date_registration: string;
    date_end: string;
    note: string;
    dns_provider: string;
    dns_provider_login: string;
    is_active_for_ping: number;
    is_active_for_code: number;

    created_at: string;
    updated_at: string;
}
