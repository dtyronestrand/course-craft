<?php
namespace App;
/**
 * Represents common three-letter course prefixes used in academic institutions.
 * This can be useful for standardized course catalog management.
 */
enum CoursePrefix: string
{
    case ART = 'ART'; // Art History, Studio Art, etc.
    case BIO = 'BIO'; // Biology
    case BUS = 'BUS'; // Business Administration
    case CHE = 'CHE'; // Chemistry
    case CIS = 'CIS'; // Computer Information Systems
    case COM = 'COM'; // Communications
    case CSC = 'CSC'; // Computer Science
    case ECN = 'ECN'; // Economics
    case EDU = 'EDU'; // Education
    case EGR = 'EGR'; // Engineering
    case ENG = 'ENG'; // English
    case FIN = 'FIN'; // Finance
    case FRE = 'FRE'; // French
    case GEO = 'GEO'; // Geography
    case HIS = 'HIS'; // History
    case JPN = 'JPN'; // Japanese
    case LAT = 'LAT'; // Latin
    case MAT = 'MAT'; // Mathematics
    case MKT = 'MKT'; // Marketing
    case MUS = 'MUS'; // Music
    case PHI = 'PHI'; // Philosophy
    case PHY = 'PHY'; // Physics
    case POL = 'POL'; // Political Science
    case PSY = 'PSY'; // Psychology
    case SOC = 'SOC'; // Sociology
}