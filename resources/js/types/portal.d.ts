export interface Portal {
    id: number;
    name: string;
    short_url: string;
    bot_url: string;
    note: string;
    topic_id: number;
    topic?: Topic;
    portal_partner_links_count: number;
    portal_partner_links?: PortalPartnerLinks[];
    created_at: string;
    updated_at: string;
    deleted_at: string;
}
