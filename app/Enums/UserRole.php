<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case AdmissionCommittee = 'admission-committee';
    case AdmissionFinance = 'admission-finance';
}
