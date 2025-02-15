interface PartnerLink {
    id: number;
    name: string;
    url: string;
    country_code: string[];
    countries: Country[];
    helper_text: string;
    partner_id: number;
    topic_id: number;
    partner?: Partner;
    topic?: Topic;
    created_at: string;
    updated_at: string;
    deleted_at: string;

}
