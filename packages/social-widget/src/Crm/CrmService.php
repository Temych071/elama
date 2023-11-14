<?php

namespace SocialWidget\Crm;

interface CrmService
{
    public function createLead(CreateLeadCommand $command): ?string;
    public function disableIntegration(): void;
}


