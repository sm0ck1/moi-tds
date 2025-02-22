export interface PortalPlacement {
    id: number;
    external_url: string;
    ping_counter: number;
    get_to_ping: number;
    in_search: boolean;
    created_at: string;
    updated_at: string;
    portal_id: number;
    portal: Portal;
}
