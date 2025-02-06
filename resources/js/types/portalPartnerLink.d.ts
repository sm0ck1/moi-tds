interface PortalPartnerLink {
    id: number;
    portal_id: number;
    partner_link_id: number;
    priority: number;
    is_fallback: boolean;
    conditions: {
        country?: {
            operator: 'in' | 'not';
            values: string[];
        };
        device?: {
            value: 'desktop' | 'mobile';
        };
    };
}
