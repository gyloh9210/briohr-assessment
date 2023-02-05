<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    public function getCompany(int $companyId)
    {
        $companie = json_decode(Storage::disk('local')->get('mock-data/companies.json'), true);
        return Arr::first($companie, function ($company) use ($companyId) {
            return $company['id'] === $companyId;
        });
    }
}
