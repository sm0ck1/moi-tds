export interface Partner {
    id: number;
    name: string;
    url: string;
    login: string;
    password: string;
    note: string;
    deleted_at: string;
    partner_links?: PartnerLinks[];
}
