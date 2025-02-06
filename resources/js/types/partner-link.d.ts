interface PartnerLink {
    id: number;
    name: string;
    url: string;
    helper_text: string;
    partner_id: number;
    topic_id: number;
    partner?: Partner;
    topic?: Topic;
    created_at: string;
    updated_at: string;
    deleted_at: string;

}
