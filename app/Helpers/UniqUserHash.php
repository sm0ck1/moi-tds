<?php

namespace App\Helpers;


class UniqUserHash
{

    private string $portal_id;
    private string $ip_address;
    private string $user_agent;
    private string $visit_date;

    public function __construct(
        string $portal_id = null,
        string $ip_address = null,
        string $user_agent = null,
        string $visit_date = null
    )
    {
        $this->portal_id = $portal_id;
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
        $this->visit_date = $visit_date;

    }

    public function generate(): string | null
    {
        if (empty($this->portal_id) || empty($this->ip_address) || empty($this->user_agent) || empty($this->visit_date)) {
            return null;
        }

        return md5(
            $this->portal_id .
            $this->ip_address .
            $this->user_agent .
            $this->visit_date
        );
    }

}
