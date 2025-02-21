import {Portal} from "@/types/portal";

export declare interface VisitUser {
    id: number;
    ip_address: string;
    user_agent: string;
    landing: string;
    tracker: string;
    referrer: string;
    visit_date: string;
    country_code: string;
    device_type: string;
    visit_count: string;
    external_url: string;
    confirm_click: number;
    metrics: any;
    portal_partner_link_link_id: number;
    portal_partner_link: PartnerLink;
    portal_id: number;
    portal: Portal
    created_at: string;
    updated_at: string;
}
